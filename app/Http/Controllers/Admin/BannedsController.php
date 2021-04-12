<?php namespace App\Http\Controllers\Admin;

use App\Banned;
use App\BanReg;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BannedsController extends Controller {

    public function index(Request $request) {

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $q = "%".$query."%";
            $banneds = Banned::where("username", "LIKE", $q)->orWhere("by", "LIKE", $q)
                ->orWhere("ip", "LIKE", $q)->orWhere("device", "LIKE", $q)
                ->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $banneds = Banned::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.banneds', compact('banneds', 'query'));
    }

    public function postAdd(Request $request) {
        if(!Auth::check()) return;

        $ip = $request->input("ip");
        $device = $request->input("device");

        if(!$ip && !$device) {
            return redirect()->back()->withErrors(["message" => "الرجاء تحديد عنوان الأيبي أو رقم الجهاز"]);
        }

        if($ip) {

            if (count(Banned::where(['ip' => $ip])->get()) === 0) {

                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
                $countryCode = (isset($query['countryCode'])) ? $query['countryCode'] : '';

                $ban = new Banned();
                $ban->username = '/';
                $ban->ip = $ip;
                $ban->finishes_at = new \Carbon\Carbon("3000-04-26");
                $ban->cause = '/';
                $ban->device = $device;
                $ban->by = 'Administration';
                $ban->country = strtolower($countryCode);
                $ban->save();

                BanReg::create([
                    "byRealName" => Auth::user()->name,
                    "byNickName" => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                    "toRealName" => $ip,
                    "toNickName" => $ip,
                    "reason" => '/',
                    "actionType" => "ban"
                ]);
            }

        } else if($device) {
            if (count(Banned::where(['device' => $device])->get()) === 0) {
                $ban = new Banned();
                $ban->username = '/';
                $ban->ip = $ip ?: "";
                $ban->device = $device;
                $ban->finishes_at = new \Carbon\Carbon("2020-04-26");
                $ban->cause = '/';
                $ban->by = 'Administration';
                $ban->country = '';
                $ban->save();

                BanReg::create([
                    "byRealName" => Auth::user()->name,
                    "byNickName" => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                    "toRealName" => $device,
                    "toNickName" => $device,
                    "reason" => '/',
                    "actionType" => "ban"
                ]);

            }
        }

        return redirect()->back();
    }

    public function postUnban(Request $request) {
        if(!Auth::check()) return;
        if($request->input('action') == 'unbanUser-'.env("ADMIN_HASH")) {
            $results  = [];
            $unbaneds = [];
            $unbanedIps = [];
            $unbanedsFps= [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    $banned = Banned::findOrFail($id);
                    BanReg::create([
                        "byRealName"    => Auth::user()->name,
                        "byNickName"    => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                        "toRealName"    => strlen($banned->username) > 2 ? $banned->username : $banned->ip,
                        "toNickName"    => strlen($banned->username) > 2 ? $banned->username : $banned->ip,
                        "actionType"    => "unban"
                    ]);

                    if($banned && $banned->user_id) {
                        $user = User::find($banned->user_id);

                        if($user) {
                            $user->denied = false;
                            $user->save();
                        }

                    }

                    $banned->delete();
                    $unbaneds[] = $id;
                    $unbanedIps[] = $banned->ip;
                    $unbanedsFps[] = $banned->device;
                } catch(\Exception $e) {}
            }
            $results['error'] = false;
            $results['unbaneds'] = $unbaneds;
            $results['unbanedIps'] = $unbanedIps;
            $results['unbanedsFps'] = $unbanedsFps;
            return json_encode($results);
        }
    }

    public function postSearch(Request $request) {
        if(!Auth::check()) return;
        $results = [];
        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

        if($request->input('action') == 'searchBanned-'.env("ADMIN_HASH")) {
            $query = $pdo->prepare("SELECT * FROM banneds WHERE ip = ?");
            $query->execute(array($request->input('keyword')));
            $banneds = $query->fetchAll();

            $results['error'] = false;
            $results['banneds'] = $banneds;
            $results['length'] = count($banneds);

            echo json_encode($results);

        } else if($request->input('action') == 'allBanneds-'.env("ADMIN_HASH")) {
            $query = $pdo->prepare("SELECT * FROM banneds");
            $query->execute(array());
            $banneds = $query->fetchAll();

            $results['error'] = false;
            $results['banneds'] = $banneds;
            $results['length'] = count($banneds);

            echo json_encode($results);

        }
    }

    public function postChange(Request $request) {
        if(!Auth::check()) return;
        if($request->input('action') == 'changeBanned-'.env("ADMIN_HASH")) {
            $pdo = DB::connection()->getPdo();
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $ids = $request->input('ids');
            $results = [];
            foreach($ids as $id) {
                $query = $pdo->prepare('UPDATE banneds SET finishes_at = ? WHERE id = ?');
                $query->execute([new \Carbon\Carbon($request->input('finishes_at')), $id]);
            }
            $results['error'] = false;
            echo json_encode($results);
        }
    }

}