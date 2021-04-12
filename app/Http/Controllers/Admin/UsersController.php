<?php namespace App\Http\Controllers\Admin;

use App\Banned;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RegisterRequest;
use Illuminate\Validation\Rule;

class UsersController extends Controller {

    public function index(Request $request) {

        $roleFilter = $request->input("role");
        $query = $request->input("q");

        if($roleFilter) {
            $users = User::where("role_id", $roleFilter)->orderBy("created_at", 'DESC')
                ->paginate(50)->appends(Input::except(['page']));
        } else if($query) {
            $users = User::where("ip", "like", "%".$query."%")
                ->orWhere("name", "like", "%".$query."%")->orWhere("decoration", "like", "%".$query."%")
                ->orWhere("device", "like", "%".$query."%")->orderBy("created_at", 'desc')
                ->paginate(50)->appends(Input::except(['page']));
        } else {
            $users = User::orderBy("created_at", 'DESC')->paginate(50)->appends(Input::except(['page']));
        }

        $page = $request->input("page") ?: 0;
        $roles = Role::all();

        return view('admin.pages.users', compact(['users', 'page', 'roles', 'roleFilter', 'query']));
    }

    public function getLogin() {

        return view('admin.pages.login');
    }

    public function postLogin(Request $request) {

        $this->validate($request, [
            'username' => 'required|min:3',
            'password' => 'required|min:5',
        ]);
        $user = User::where(['name' => $request->input('username')])->first();
        if ($user && ($user->has_ext_admin || $user->isOwner())) {
            if (Auth::attempt(['name' => $request->input('username'), 'password' => $request->input('password')], false)) {
                $user->last_login = new \Carbon\Carbon();
                $user->ip = $_SERVER["REMOTE_ADDR"];
                $user->save();

                return redirect(env("ADMIN_HASH") . '/admin/users');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function postAdd(Request $request) {

        if (!Auth::check()) return;

        $this->validate($request, [
            'username'  => ['required', 'min:2', Rule::unique("users", "name")],
            'password'   => 'required|min:6',
        ], [
            'username.required' => "الرجاء إعطاء إسم للمتسخدم",
            "username.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
            "username.unique" => "هذا الإسم محجوز, الرجاء إختيار إسم أخر",
            "password.required" => "الرجاء تحديد كلمة المرور",
            "password.min" => "كلمة المرور لا يحب أن تقل عن حرفين",
        ]);

        $role = $request->input("role");

        if(!Role::isValid($role)) {
            $role = Role::getRoleByType("basic");
        }

        $createdUser = User::create([
            "name" => htmlspecialchars($request->input("username")),
            "password" => bcrypt($request->input("password")),
            "role_id" => $role,
            "device" => "",
            "ip" => "",
            "country" => ""
        ]);

        if($createdUser) {
            return redirect()->back()->with("success_msg", "تم إنشاء العضو بنجاح");
        } else {
            return redirect()->back()->withErrors('حدث خطأ أثناء القيام بالعملية');
        }

    }

    public function postDelete(Request $request) {

        if (!Auth::check()) return;
        if ($request->input('action') == 'deleteUser-' . env("ADMIN_HASH")) {
            $results = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach ($ids as $id) {
                User::findOrNew($id)->delete();
                $deleteds[] = $id;
            }
            $results['error'] = false;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        }
    }

    public function postBan(Request $request) {

        if ($request->input('action') == 'banUser-' . env("ADMIN_HASH") && count($request->input('ids'))) {
            $results = [];
            $ids = $request->input('ids');
            $countries = $request->input('countries');
            $bannedIps = [];
            foreach ($ids as $key => $value) {
                $user = User::findOrFail($ids[$key]);
                $user->denied = true;
                $user->save();
                if ($user->ip || $user->fingerprint) {
                    if (count(Banned::where(['ip' => $user->ip])->get()) === 0) {
                        Banned::create([
                            'username'    => $user->username,
                            'ip'          => $user->ip,
                            'fingerprint' => $user->fingerprint,
                            'cause'       => $request->input('cause', ''),
                            'country'     => $countries[$key],
                            'finishes_at' => new \Carbon\Carbon($request->input('finishes_at', '')),
                            'by'          => 'Administration',
                        ]);
                        $bannedIps[] = $user->ip;
                    }
                }
            }
            if (count($bannedIps)) {
                $results['error'] = false;
                $results['message'] = 'user(s) banned successfully';
                $results['ips'] = $bannedIps;
            } else {
                $results['error'] = true;
                $results['message'] = 'no user banned';
            }
            echo json_encode($results);
        }
    }

    public function postSave(Request $request) {

        if (!Auth::check()) return;
        $this->validate($request, [
            'action' => 'required',
            'ids'    => 'required',
            'roles'  => 'required',
        ]);
        if ($request->input('action') == 'saveChanges-' . env("ADMIN_HASH")) {
            $pdo = DB::connection()->getPdo();
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $ids = $request->input('ids');
            $roles = $request->input('roles');
            $results = [];
            $resultIds = [];
            $resultRoles = [];
            foreach ($ids as $key => $value) {
                $query = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
                $r = $query->execute([$roles[$key], $ids[$key]]);
                if ($r) {
                    $resultIds[] = $ids[$key];
                    $resultRoles[] = $roles[$key];
                }
            }
            if (count($resultIds) && count($resultRoles)) {
                $results['error'] = false;
                $results['message'] = "user(s) updated successfully";
                $results['ids'] = $resultIds;
                $results['roles'] = $resultRoles;
            } else {
                $results['error'] = true;
                $results['message'] = 'no user updated';
            }
            echo json_encode($results);
        }
    }

    public function pasChangePassword(Request $request) {

        if ($request->input("action") == 'changePassword-' . env("ADMIN_HASH") && $request->input("role") != "guest") {
            try {
                $user = User::find($request->input("id"));
                if ($user) {
                    $user->password = bcrypt($request->input("pass"));
                    if ($user->save()) {
                        return json_encode(["error" => false, "message" => "success", "pass" => $request->input("pass")]);
                    } else {
                        http_response_code(403);

                        return json_encode(["error" => true, "message" => "failed"]);
                    }
                } else {
                    http_response_code(403);

                    return json_encode(["error" => true, "message" => "failed"]);
                }
            } catch (\Exception $e) {
                http_response_code(403);

                return json_encode(["error" => true, "message" => $e->getMessage()]);
            }
        } else {
            http_response_code(403);

            return json_encode(["error" => true, "message" => "access denied"]);
        }
    }

    public function postRandomHash(Request $request) {

        $this->validate($request, [
            'action' => 'required',
            'value'  => 'required',
        ]);
        if ($request->input("action") == "generate-hash-" . env("ADMIN_HASH")) {
            return json_encode(["error" => false, "hash" => bcrypt($request->input("value"))]);
        }
    }

    public function getLogout() {
        Auth::logout();
        return redirect(env("ADMIN_HASH") . "/admin");
    }

}