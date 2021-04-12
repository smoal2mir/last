<?php

namespace App\Http\Controllers;

use App\Banned;
use App\BanReg;
use App\BtnBg;
use App\Face;
use App\FilteredWord;
use App\Gift;
use App\Option;
use App\OrderReg;
use App\Permission;
use App\RevealName;
use App\Role;
use App\Room;
use App\RoomReg;
use App\SelfMessage;
use App\Settings;
use App\Shortcut;
use App\User;
use App\Wall;
use App\Word;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FadminController extends Controller {

    public function __construct() {
        $this->settings = Settings::findOrNew(1);
    }

    /* Home Page Related opations */
    public function getHome($token) {
        if(!$this->isAuthorized($token, 'admin')) return $this->renderNoPermissions();
        return view("fadmin.pages.home", compact(["token"]));
    }

    /* Reg Related opations */
    public function getReg($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_reg')) return $this->renderNoPermissions();
        $reg = [];
        $keyword = "";

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $reg = RevealName::where("ip", "like", "%".$keyword."%")
                ->orWhere("username", "like", "%".$keyword."%")->orWhere("decoration", "like", "%".$keyword."%")
                ->orWhere("device", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $reg = RevealName::orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.reg", compact(["reg", "token", "keyword"]));

    }

    public function postReg($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_reg');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");

        if(!$this->isAuthorized($token) || !$id) {
            return response(["error" => true, "isTruncated" => false, "msg", "no permission"], 403);
        }

        if($id == "all") {
            RevealName::truncate();
            return response(["error" => false, "isTruncated" => true, "msg", "success"], 200);
        } else {
            $reg = RevealName::find($id);
            if ($reg) {
                $reg->delete();
                return response(["error" => false, "isTruncated" => false, "msg", "success"], 200);
            } else {
                return response(["error" => true, "isTruncated" => false, "msg", "error"], 400);
            }
        }

    }

    /* Events Related opations */
    public function getEvents($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_events')) return $this->renderNoPermissions();
        $events = [];
        $keyword = "";
        $filter = "";

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $events = OrderReg::where("byRealName", "like", "%".$keyword."%")
                ->orWhere("toRealName", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else if($request->input("filter")) {
            $filter = htmlspecialchars($request->input("filter"));
            $events = OrderReg::where("actionType", $filter)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $events = OrderReg::orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.events", compact(["events", "token", "keyword", "filter"]));

    }

    public function postEvents($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_events');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");

        if(!$this->isAuthorized($token) || !$id) {
            return response(["error" => true, "isTruncated" => false, "msg", "no permission"], 403);
        }

        if($id == "all") {
            OrderReg::truncate();
            return response(["error" => false, "isTruncated" => true, "msg", "success"], 200);
        } else {
            $event = OrderReg::find($id);
            if ($event) {
                $event->delete();
                return response(["error" => false, "isTruncated" => false, "msg", "success"], 200);
            } else {
                return response(["error" => true, "isTruncated" => false, "msg", "error"], 400);
            }
        }

    }

    /* Users Related opations */
    public function getUsers($token, Request $request) {
        $user = $this->isAuthorized($token, "admin_users");
        if(!$user) return $this->renderNoPermissions();
        $users = [];
        $keyword = "";
        $filter = "";
        $_roles = $user->getRoleToUpgrade();
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", false)->where("ip", "like", "%".$keyword."%")
                ->orWhere("name", "like", "%".$keyword."%")->whereIn("role_id", $roleIDs)->where("is_virtual", false)
                ->orWhere("decoration", "like", "%".$keyword."%")->whereIn("role_id", $roleIDs)->where("is_virtual", false)
                ->orWhere("device", "like", "%".$keyword."%")->whereIn("role_id", $roleIDs)->where("is_virtual", false)
                ->orderBy("created_at", 'desc')->limit(1000)->get();
        } else if($request->input("filter")) {
            $filter = htmlspecialchars($request->input("filter"));
            $users = User::where("role_id", $filter)->where("is_virtual", false)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", false)->orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.users", compact(["users", "token", "keyword", "filter", "_roles"]));

    }

    public function postAddUser($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_users');
        if(!$user) return $this->renderNoPermissions();

        $this->validate($request, [
            'name'  => ['required', 'min:2', Rule::unique("users", "name")],
            'password'   => 'required|min:6',
        ], [
            'name.required' => "الرجاء إعطاء إسم للمتسخدم",
            "name.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
            "name.unique" => "هذا الإسم محجوز, الرجاء إختيار إسم أخر",
            "password.required" => "الرجاء تحديد كلمة المرور",
            "password.min" => "كلمة المرور لا يحب أن تقل عن حرفين",
        ]);

        $role = $request->input("role");

        if(!Role::isValid($role)) {
            $role = Role::getRoleByType("basic");
        }

        $createdUser = User::create([
            "name" => htmlspecialchars($request->input("name")),
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

    public function postUpgradeUsersRole($token, Request $request) {
        $user = $this->isAuthorized($token, "orders_upgrade");
        if(!$user) return $this->renderNoPermissions(true);

        $role = Role::find($request->input("roleId"));
        if(!$role) return json_encode(["error" => true, "msg" => "no super"]);

        $_roles = $user->getRoleToUpgrade();
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if(!in_array($role->id, $roleIDs)) return $this->renderNoPermissions(true);

        $targetUser = User::find($request->input("userId"));
        if(!$targetUser) return json_encode(["error" => true, "msg" => "no super"]);

        if($targetUser->isBasic()) {
            $targetUser->subscription_end = Carbon::now()->addDays(7);
        }

        $targetUser->role_id = $role->id;
        if($targetUser->save()) {

            OrderReg::create([
                "byRealName" => $user->name,
                "byNickName" => $user->decoration ?: "/",
                "toRealName" => $targetUser->name,
                "toNickName" => $targetUser->decoration ?: "/",
                "notes" => "تم تغيير رتبته إلى " . Role::getRoleName($targetUser->role_id),
                "actionType" => "edit-role",
            ]);

            return json_encode(["error" => false, "user_id" => $targetUser->id, "role" => $targetUser->role_id]);
        } else {
            return json_encode(["error" => true]);
        }

    }

    public function postDeleteUser($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_users_remove');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input('id');

        $user = User::find($id);
        if(!$user) {
            return response(["error" => true, "message" => "هذا المستخدم غير مسجل لدينا"], 200);
        }

        $user->delete();
        return response(["error" => false, "message" => "تمت عملية الحذف بنجاح"], 200);
    }

    public function postChangeUserPassword($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_users_edit');
        if(!$user) return $this->renderNoPermissions(true);

        $this->validate($request, [
            'id'  => ['required', Rule::exists("users", "id")],
            'password'   => 'required|min:6',
        ], [
            'id.required' => "لم يتم التعرف على هذا المستخدم, الرجاء التأكد من المدخلات",
            "id.exists" => "هذا العضو غير مسجل لدينا",
            "password.required" => "الرجاء تحديد كلمة المرور",
            "password.min" => "كلمة المرور لا يحب أن تقل عن حرفين",
        ]);

        $user = User::find($request->input("id"));
        if(!$user) {
            return response(["error" => true, "message" => "هذا المستخدم غير مسجل لدينا"], 200);
        }

        $user->password = bcrypt($request->input("password"));
        if($user->save()) {
            return response(["error" => false, "message" => "تم تغيير كلمة المرور بنجاح"], 200);
        } else {
            return response(["error" => true, "message" => "حدث خطأ أثناء القيام بالعملية"], 200);
        }

    }

    /* Virtual Users Related Options */
    public function getVirtualUsers($token, Request $request) {
        $user = $this->isAuthorized($token, "admin_virtuals");
        if(!$user) return $this->renderNoPermissions();
        $users = [];
        $keyword = "";
        $filter = "";
        $rooms = Room::all();
        $_roles = $user->getRoleToUpgrade();
        $countries = getCountries();
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", true)->where("ip", "like", "%".$keyword."%")
                ->orWhere("name", "like", "%".$keyword."%")->where("is_virtual", true)->whereIn("role_id", $roleIDs)
                ->orWhere("decoration", "like", "%".$keyword."%")->where("is_virtual", true)->whereIn("role_id", $roleIDs)
                ->orWhere("device", "like", "%".$keyword."%")->where("is_virtual", true)->whereIn("role_id", $roleIDs)
                ->orderBy("created_at", 'desc')->limit(1000)->get();
        } else if($request->input("filter")) {
            $filter = htmlspecialchars($request->input("filter"));
            $users = User::where("role_id", $filter)->where("is_virtual", true)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", true)->orderBy("created_at", 'desc')->limit(1000)->get();
        }

        $settings = $this->settings;

        return view("fadmin.pages.virtuals", compact(["users", "token", "keyword", "filter", "_roles", "countries", "rooms", "settings"]));
    }

    public function postAddVirtualUser($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_virtuals');
        if(!$user) return $this->renderNoPermissions();

        $this->validate($request, [
            'name'  => ['required', 'min:2', Rule::unique("users", "name")]
        ], [
            'name.required' => "الرجاء إعطاء إسم للمتسخدم",
            "name.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
            "name.unique" => "هذا الإسم محجوز, الرجاء إختيار إسم أخر",
        ]);

        $role = $request->input("role");
        $room = $request->input("room");

        if(!Role::isValid($role)) {
            $role = Role::getRoleByType("basic");
        }

        if(!Room::find($room)) {
            $role = 1;
        }

        $createdUser = User::create([
            "name" => htmlspecialchars($request->input("name")),
            "password" => bcrypt("#virtuals_default_password#"),
            "role_id" => $role,
            "device" => str_random(64),
            "ip" => long2ip(mt_rand()),
            "is_virtual" => true,
            "default_room" => $room,
            "country" => $request->input("country") ?: "SA"
        ]);

        if($createdUser) {
            return redirect()->back()->with("success_msg", "تم إنشاء العضو بنجاح")->with("user", $createdUser->toJson());
        } else {
            return redirect()->back()->withErrors('حدث خطأ أثناء القيام بالعملية');
        }

    }

    public function postEditVirtualUsers($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_virtuals_edit');
        if(!$user) return $this->renderNoPermissions(true);

        $ids            = $request->input('ids');
        $names          = $request->input('names');
        $rols           = $request->input('roles');
        $status         = $request->input('status');
        $rooms   	    = $request->input('rooms');
        $ips            = $request->input('ips');
        $countries      = $request->input('countries');
        $resultUsers = [];

        foreach ($ids as $key => $value) {
            try {

                $user = User::find($value);

                if(!$user || !$user->is_virtual) continue;

                $user->name = htmlspecialchars($names[$key]);
                $user->role_id = $rols[$key];
                $user->status = htmlspecialchars($status[$key]);
                $user->default_room = $rooms[$key];
                $user->ip = htmlspecialchars($ips[$key]);
                $user->country = $countries[$key];
                $user->save();

                $resultUsers[] = $user;

            } catch(\Exception $e) {}
        }

        if(count($resultUsers)) {
            return response(["error" => false, "message" => "user(s) updated successfully", "users" => $resultUsers], 200);
        } else {
            return response(["error" => true, "message" => "no user updated"], 200);
        }

    }

    public function postDeleteVirtualUser($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_virtuals_remove');
        if(!$user) return $this->renderNoPermissions(true);

        $results = [];
        $deleteds = [];
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            try {
                $user = User::find($id);
                if(!$user || !$user->is_virtual) continue;

                $user->delete();
                $deleteds[] = $id;
            } catch(\Exception $e) {}
        }

        $results['error'] = false;
        $results['deleteds'] = $deleteds;
        return json_encode($results);
    }

    public function postChangeVirtualUserAvatar($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_virtuals_edit');
        if(!$user) return $this->renderNoPermissions(true);

        $user = User::find($request->input("user_id"));
        if(!$user) return json_encode(["error" => true, "msg", "unknown user"]);

        $result = [];
        if(isset($_FILES["flag"]['tmp_name'])) {
            $tmp_name = $_FILES["flag"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["flag"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file on the server
            $array = explode('.', $_FILES["flag"]['name']);
            end($array);
            $icon = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/avatars/'.$icon)) {

                if(str_contains(strtolower($icon), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/avatars/".$icon);
                }

                if($user->avatar && !str_contains($user->avatar, "default")) {
                    try {
                        unlink('uploads/avatars/'.$user->avatar);
                    } catch(\Exception $e) {}
                }

                $user->avatar = $icon;
                $user->save();

                removeMaliciousFiles("uploads/avatars/");

                return response(["error" => false, "message" => "success", "avatar" => $icon, "id" => $user->id, "user" => $user]);

            } else {
                removeMaliciousFiles("uploads/avatars/");
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles("uploads/avatars/");
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function postVirtualUsersSettings($token, Request $request) {

        if(!$this->isAuthorized( $token, 'admin_virtuals_edit')) return $this->renderNoPermissions();

        $this->settings->virtuals_count = $request->input("virtuals_count");
        $this->settings->disable_virtuals = $request->has("disable_virtuals");
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    /* Rooms Related opations */
    public function getRooms($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_rooms')) return $this->renderNoPermissions();

        $rooms = Room::orderBy("created_at", "desc")->get();

        if($request->input("query")) {
            $keyword = $request->input("query");
            $rooms = Room::where("name", "like", "%".$keyword."%")->orWhere("description", "like", "%".$keyword."%")
                ->orWhere("welcome", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $rooms = Room::orderBy("created_at", "desc")->get();
        }

        return view("fadmin.pages.rooms", compact(["token", "rooms"]));
    }

    public function postAddRoom($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_rooms');
        if(!$user) return $this->renderNoPermissions();

        $this->validate($request, [
            'name'  => 'required',
            'capacity'   => 'numeric|min:2|max:200',
            'target'   => 'in:all,supers',
        ], [
            'name.required' => "الرجاء إختيار إسم للغرفة",
            "max.numeric" => "أقصى عدد للزوار يجب أن يكون عبارة عن رقم",
            "capacity.min" => "عدد الزوار لا يجب أن يقل عن 2",
            "capacity.max" => "عدد الزوار لا يجب أن يتجاوز 200",
            "target.in" => "ألرجاء التأكد من نوع الزوار"
        ]);

        if (count(Room::where(['name' => $request->input('name')])->get()) === 0) {
            $room = new Room();
            $room->name         = htmlentities($request->input('name'));
            $room->welcome      = htmlentities($request->input('welcome'));
            $room->password     = htmlentities($request->input('password'));
            $room->description  = "";
            $room->capacity     = htmlentities($request->input('capacity'));
            $room->target       = htmlentities($request->input('target'));
            $room->save();

            RoomReg::create([
                "byRealName"    => $user->name,
                "byNickName"    => $user->decoration ?: $user->name,
                "roomName"      => htmlentities($request->input('name')),
                "actionType"    => "add"
            ]);

            return redirect()->back()->with('addedRoom', $room)->with("success_msg", "تم إنشاء الغرفة بنجاح")->with("room", $room->toJson());
        } else {
            return redirect()->back()->withErrors('هذه الغرفة موجودة من قبل');
        }

    }

    public function postDeleteRooms($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_rooms_remove');
        if(!$user) return $this->renderNoPermissions(true);

        $results = [];
        $deleteds = [];
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            try {
                if($id == 1) continue;
                $room = Room::find($id);
                if(!$room) continue;

                RoomReg::create([
                    "byRealName"    => $user->name,
                    "byNickName"    => $user->decoration ? $user->decoration : $user->name,
                    "roomName"      => $room->name,
                    "actionType"    => "remove"
                ]);

                $room->delete();
                $deleteds[] = $id;
            } catch(\Exception $e) {}
        }

        $results['error'] = false;
        $results['deleteds'] = $deleteds;
        return json_encode($results);
    }

    public function postEditRooms($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_rooms_edit');
        if(!$user) return $this->renderNoPermissions(true);

        $ids        = $request->input('ids');
        $names      = $request->input('names');
        $passwords  = $request->input('passwords');
        $welcomes   = $request->input('welcomes');
        $descs   	= $request->input('descs');
        $capacities = $request->input('capacities');
        $targets    = $request->input('targets');
        $resultRooms = [];

        foreach ($ids as $key => $value) {
            try {

                $room = Room::find($value);

                if(!$room) continue;

                $room->name = htmlspecialchars($names[$key]);
                $room->password = htmlspecialchars($passwords[$key]);
                $room->welcome = htmlspecialchars($welcomes[$key]);
                $room->capacity = htmlspecialchars($capacities[$key]);
                $room->target = htmlspecialchars($targets[$key]);
                $room->description = htmlspecialchars($descs[$key]);
                $room->save();

                $resultRooms[] = $room;

                RoomReg::create([
                    "byRealName"    => $user->name,
                    "byNickName"    => $user->decoration ?: $user->name,
                    "roomName"      => htmlspecialchars($names[$key]),
                    "actionType"    => "edit"
                ]);

            } catch(\Exception $e) {}
        }

        if(count($resultRooms)) {
            return response(["error" => false, "message" => "room(s) updated successfully", "rooms" => $resultRooms], 200);
        } else {
            return response(["error" => true, "message" => "no room updated"], 200);
        }

    }

    public function postChangeRoomFlag($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_rooms_edit');
        if(!$user) return $this->renderNoPermissions(true);

        $room = Room::find($request->input("room_id"));
        if(!$room) return json_encode(["error" => true, "msg", "unknown room"]);

        $result = [];
        if(isset($_FILES["flag"]['tmp_name'])) {
            $tmp_name = $_FILES["flag"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["flag"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file on the server
            $array = explode('.', $_FILES["flag"]['name']);
            end($array);
            $icon = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/rooms/'.$icon)) {

                if(str_contains(strtolower($icon), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/rooms/".$icon);
                }

                if($room->flag && !str_contains($room->flag, "default")) {
                    try {
                        unlink('uploads/rooms/'.$room->flag);
                    } catch(\Exception $e) {}
                }

                $room->flag = $icon;
                $room->save();

                removeMaliciousFiles("uploads/rooms/");

                return response(["error" => false, "message" => "success", "flag" => $icon, "id" => $room->id, "room" => $room]);

            } else {
                removeMaliciousFiles("uploads/rooms/");
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles("uploads/rooms/");
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function postSettingsChangeDefaultRoomFlag($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["flag"]['tmp_name'])) {
            $tmp_name = $_FILES["flag"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["flag"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["flag"]['name']);
            end($array);
            $flag = randomString(16).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/rooms/'.$flag)) {

                if(str_contains(strtolower($flag), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/rooms/".$flag);
                    removeMaliciousFiles('uploads/rooms/');
                    return response(["error" => true, "message" => "error_image_type"]);
                }

                if($this->settings->default_rooms_flag) {
                    try {
                        unlink("uploads/rooms/" . $this->settings->default_rooms_flag);
                    } catch(\Exception $e) {}
                }

                $this->settings->default_rooms_flag = $flag;
                $this->settings->save();

                removeMaliciousFiles('uploads/rooms/');

                return response(["error" => false, "message" => "success", "flag" => $flag]);

            } else {
                removeMaliciousFiles('uploads/rooms/');
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles('uploads/rooms/');
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    /* Banneds Related opations */
    public function getBan($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_banneds')) return $this->renderNoPermissions();
        $banneds = [];
        $keyword = "";

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $banneds = Banned::where("ip", "like", "%".$keyword."%")
                ->orWhere("username", "like", "%".$keyword."%")->orWhere("by", "like", "%".$keyword."%")
                ->orWhere("device", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $banneds = Banned::orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.ban", compact(["banneds", "token", "keyword",]));

    }

    public function postBan($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_banneds_remove');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");

        if(!$this->isAuthorized($token) || !$id) {
            return response(["error" => true, "isTruncated" => false, "msg", "no permission"], 403);
        }

        if($id == "all") {
            Banned::truncate();

            OrderReg::create([
                "byRealName" => $user->name,
                "byNickName" => $user->decoration ?: $user->name,
                "toRealName" => "/",
                "toNickName" => "/",
                "notes" => "فك الحظر عن الجميع",
                "actionType" => "unban",
            ]);

            return response(["error" => false, "isTruncated" => true, "msg", "success"], 200);
        } else {
            $banned = Banned::find($id);
            if ($banned) {
                $banned->delete();

                OrderReg::create([
                    "byRealName" => $user->name,
                    "byNickName" => $user->decoration ?: $user->name,
                    "toRealName" => $banned->username ?: "/",
                    "toNickName" => $banned->username ?: "/",
                    "notes" => "/",
                    "actionType" => "unban",
                ]);

                return response(["error" => false, "ip" => $banned->ip, "device" => $banned->device, "isTruncated" => false, "msg", "success"], 200);
            } else {
                return response(["error" => true, "isTruncated" => false, "msg", "error"], 400);
            }
        }

    }

    public function postBanDeviceOrIp($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_banneds');
        if(!$user) return $this->renderNoPermissions();

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
                $ban->finishes_at = new \Carbon\Carbon("2020-04-26");
                $ban->cause = '/';
                $ban->device = $device ?: "";
                $ban->by = 'Administration';
                $ban->country = strtolower($countryCode);
                $ban->save();

                BanReg::create([
                    "byRealName" => $user->name,
                    "byNickName" => $user->decoration ? $user->decoration : $user->name,
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
                    "byRealName" => $user->name,
                    "byNickName" => $user->decoration ? $user->decoration : $user->name,
                    "toRealName" => $device,
                    "toNickName" => $device,
                    "reason" => '/',
                    "actionType" => "ban"
                ]);

            }
        }

        return redirect()->back();
    }

    /* Permissions Related opations */
    public function getPermissions($token, Request $request) {
        $user = $this->isAuthorized($token, 'edit_permissions');
        if(!$user) return $this->renderNoPermissions();

        $roles = Role::orderBy("power", "DESC")->get();

        if($request->input("r")) {
            $role = Role::find($request->input("r"));
        } else {
            $role = Role::find($user->role_id);
        }

        if(!$role) return redirect()->back();

        $privileges = null;
        if($role) $privileges = Permission::where("role_id", $role->id)->first();
        if(!$privileges) $privileges = Permission::create(["role_id" => $role->id]);

        return view("fadmin.pages.privileges", compact(["roles", "role", "privileges", "token"]));
    }

    public function postPermissions($token, Request $request) {
        if(!$this->isAuthorized($token, 'edit_permissions')) return $this->renderNoPermissions();
        if($request->input("roleId")) {
            return $this->removeRole($request->input("roleId"));
        } else if($request->input("roleName")) {
            $validator = Validator::make($request->input(), [
                "roleName" => "required|unique:roles,name"
            ]);
            if($validator->fails()) {
                $messages = $validator->messages();
                return json_encode(["error" => true, "msg" => $messages->first()]);
            }
            return $this->addRole($request->input("roleName"));
        }
    }

    public function postSavePermissions($token, Request $request) {
        $user = $this->isAuthorized($token, 'edit_permissions');
        if(!$user) return $this->renderNoPermissions(true);

        $currentUsersRole = Role::find($user->role_id);
        $role = Role::find($request->input("role"));
        if(!$role) return json_encode(["error" => true, "msg", "unknown role"]);
        if(!$user->isGreaterThan($role->id) || !$currentUsersRole) return $this->renderNoPermissions(true);

        $valideRoles = [];
        foreach(Role::all() as $rl) { $valideRoles[] = $rl->id; }

        $permission = Permission::where("role_id", $role->id)->first();
        if(!$permission) $permission = Permission::create(["role_id" => $role->id]);

        $data = $request->input();

        if($currentUsersRole->power > intval($data["role_power"]) || $currentUsersRole->id == $role->id) {
            $role->power = intval($data["role_power"]);
            $role->save();
        }

        $permission->role_id = $role->id;
        $permission->orders_excpel = sanitizeAutorizationValue($data["orders_excpel"], $data["orders_excpel_unit"], "integer");
        $permission->orders_ban = sanitizeAutorizationValue($data["orders_ban"], $data["orders_ban_unit"], "integer");
        $permission->orders_notify = sanitizeAutorizationValue($data["orders_notify"], $data["orders_notify_unit"], "integer");
        $permission->orders_gift = sanitizeAutorizationValue($data["orders_gift"], $data["orders_gift_unit"], "integer");
        $permission->orders_remove_avatar = sanitizeAutorizationValue($data["orders_remove_avatar"], $data["orders_remove_avatar_unit"], "integer");
        $permission->orders_change_decoartion = sanitizeAutorizationValue($data["orders_change_decoartion"], $data["orders_change_decoartion_unit"], "integer");
        $permission->orders_excpel_from_room = sanitizeAutorizationValue($data["orders_excpel_from_room"], $data["orders_excpel_from_room_unit"], "integer");
        $permission->room_create = sanitizeAutorizationValue($data["room_create"], $data["room_create_unit"], "integer");
        $permission->room_edit = sanitizeAutorizationValue($data["room_edit"], $data["room_edit_unit"], "integer");
        $permission->room_remove = sanitizeAutorizationValue($data["room_remove"], $data["room_remove_unit"], "integer");
        $permission->room_max_pertinents = sanitizeAutorizationValue($data["room_max_pertinents"], $data["room_max_pertinents_unit"], "integer");
        $permission->send_ad = sanitizeAutorizationValue($data["send_ad"], $data["send_ad_unit"], "integer");
        $permission->wall_remove = sanitizeAutorizationValue($data["wall_remove"], $data["wall_remove_unit"], "integer");
        $permission->wall_interval = sanitizeAutorizationValue($data["wall_interval"], $data["wall_interval_unit"], "integer");
        $permission->public_msg_remove = sanitizeAutorizationValue($data["public_msg_remove"], $data["public_msg_remove_unit"], "integer");
        $permission->radio_speak_time = intval($data["radio_speak_time"]);
        $permission->stop_radio = sanitizeAutorizationValue($data["stop_radio"], null, "boolean");

//        $permission->change_btns_bg = sanitizeAutorizationValue($data["change_btns_bg"], null, "boolean");
        $permission->show_ip = sanitizeAutorizationValue($data["show_ip"], null, "boolean");
        $permission->orders_upgrade = sanitizeAutorizationValue($data["orders_upgrade"], null, "boolean");
        $permission->wall_banner = sanitizeAutorizationValue($data["wall_banner"], null, "boolean");
        $permission->hidden = sanitizeAutorizationValue($data["hidden"], null, "boolean");
        $permission->edit_permissions = sanitizeAutorizationValue($data["edit_permissions"], null, "boolean");
        $permission->reveal_names = sanitizeAutorizationValue($data["reveal_names"], null, "boolean");
        $permission->open_private = sanitizeAutorizationValue($data["open_private"], null, "boolean");
        $permission->open_full_rooms = sanitizeAutorizationValue($data["open_full_rooms"], null, "boolean");
        $permission->open_locked_rooms = sanitizeAutorizationValue($data["open_locked_rooms"], null, "boolean");
        $permission->like_controls = sanitizeAutorizationValue($data["like_controls"], null, "boolean");
//        $permission->show_real_name = sanitizeAutorizationValue($data["show_real_name"], null, "boolean");
        $permission->orders_change_room = sanitizeAutorizationValue($data["orders_change_room"], null, "boolean");
        $permission->admin = sanitizeAutorizationValue($data["admin"], null, "boolean");
        $permission->admin_events = sanitizeAutorizationValue($data["admin_events"], null, "boolean");
        $permission->admin_reg = sanitizeAutorizationValue($data["admin_reg"], null, "boolean");
        $permission->admin_users = sanitizeAutorizationValue($data["admin_users"], null, "boolean");
        $permission->admin_users_remove = sanitizeAutorizationValue($data["admin_users_remove"], null, "boolean");
        $permission->admin_users_edit = sanitizeAutorizationValue($data["admin_users_edit"], null, "boolean");
        $permission->admin_virtuals = sanitizeAutorizationValue($data["admin_virtuals"], null, "boolean");
        $permission->admin_virtuals_remove = sanitizeAutorizationValue($data["admin_virtuals_remove"], null, "boolean");
        $permission->admin_virtuals_edit = sanitizeAutorizationValue($data["admin_virtuals_edit"], null, "boolean");
        $permission->admin_banneds = sanitizeAutorizationValue($data["admin_banneds"], null, "boolean");
        $permission->admin_banneds_remove = sanitizeAutorizationValue($data["admin_banneds_remove"], null, "boolean");
        $permission->admin_rooms = sanitizeAutorizationValue($data["admin_rooms"], null, "boolean");
        $permission->admin_rooms_edit = sanitizeAutorizationValue($data["admin_rooms_edit"], null, "boolean");
        $permission->admin_rooms_remove = sanitizeAutorizationValue($data["admin_rooms_remove"], null, "boolean");
        $permission->admin_supers = sanitizeAutorizationValue($data["admin_supers"], null, "boolean");
        $permission->admin_supers_remove = sanitizeAutorizationValue($data["admin_supers_remove"], null, "boolean");
        $permission->admin_settings = sanitizeAutorizationValue($data["admin_settings"], null, "boolean");

        $permission->orders_upgrade_roles = validateAutorizationRoles($data, $valideRoles, 'orders_upgrade_roles');
        $permission->can_see_hidden = validateAutorizationRoles($data, $valideRoles, "can_see_hidden_roles");

        if($permission->save()) {
            return json_encode(["error" => false]);
        } else {
            return json_encode(["error" => true, "msg" => "error"]);
        }

    }

    public function postPermissionsSaveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'edit_permissions');
        if(!$user) return $this->renderNoPermissions(true);

        $role = Role::find($request->input("role"));
        if(!$role) return json_encode(["error" => true, "msg", "unknown role"]);
        if(!$user->isGreaterThan($role->id)) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["icon"]['tmp_name'])) {
            $tmp_name = $_FILES["icon"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["icon"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["icon"]['name']);
            end($array);
            $icon = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/roles/'.$icon)) {

                if(str_contains(strtolower($icon), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/roles/".$icon);
                }

                $role->icon = $icon;
                $role->save();

                removeMaliciousFiles("uploads/roles/");

                return response(["error" => false, "message" => "success", "icon" => $icon, "role" => $role->id]);

            } else {
                removeMaliciousFiles("uploads/roles/");
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles("uploads/roles/");
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function postPermissionsRemoveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'edit_permissions');
        if(!$user) return $this->renderNoPermissions(true);

        $role = Role::find($request->input("roleId"));
        if(!$role) return json_encode(["error" => true, "msg", "unknown role"]);
        if(!$user->isGreaterThan($role->id)) return $this->renderNoPermissions(true);

        $iconBeforeRemove = $role->icon;
        $role->icon = null;
        $role->save();

        try {
            unlink(public_path("uploads/roles/$iconBeforeRemove"));
        } catch (\Exception $e) {}

        return json_encode(["error" => false, "message" => "success", "icon" => $role->getIcon(50), "role" => $role->id]);

    }

    /* Words Filter Related opations */
    public function getFilter($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $words = [];
        $keyword = "";
        $filter = "";

        $wordsByType = Word::getTypes();

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $words = Word::where("word", "like", "%".$keyword."%")->limit(1000)->get();
        } else if($request->input("filter") && $request->input("filter") != "all") {
            $filter = htmlspecialchars($request->input("filter"));
            $words = Word::where("type", $filter)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $words = Word::orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.filter", compact(["words", "token", "keyword", "wordsByType", "filter"]));

    }

    public function postFilterAddWord($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $request["word"] = trim(htmlentities($request->input("word")));

        $this->validate($request, [
            "word" => "required|unique:words,word|min:2",
            "type" => "required|in:allowed,denied,controlled"
        ], [
            "word.required" => "الرجاء أدخال الكملة المراد إضافتها",
            "word.unique" => "هذه الكلمة موجودة من قبل",
            "word.min" => "عدد أحرف الكلمة لا يجب أن يقل عن 2 أحرف",
            "type.required" => "الرجاء إختيار التصنيف المناسب",
            "type.in" => "الرجاء إختيار التصنيف المناسب"
        ]);

        $word = Word::create(["word" => $request->input("word"), "type" => $request->input("type")]);

        return redirect()->back()->with("success_msg", "تم إضافة الكلمة بنجاح")->with("word", $word->toJson());
    }

    public function postFilterEditWord($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $word = Word::where("id", $request->input("wordId"))->first();
        if($word) {
            $word->update(["type" => $request->input("type")]);
            return response(["error" => false, "word" => $word], 200);
        } else {
            return response(["error" => true, "word" => null], 400);
        }


    }

    public function postFilterRemoveWord($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        if(Word::where("id", $request->input("id"))->delete())
            return json_encode(["error" => false]);
        else
            return json_encode(["error" => true]);

    }

    /* Filtered Words Related opations */
    public function getFilteredWords($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $filteredWords = [];
        $keyword = "";
        $filter = "";

        $wordsByType = Word::getTypes();

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $filteredWords = FilteredWord::where("ip", "like", "%".$keyword."%")
                ->orWhere("user", "like", "%".$keyword."%")->orWhere("full_text", "like", "%".$keyword."%")
                ->orWhere("word", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else if($request->input("filter") && $request->input("filter") != "all") {
            $filter = htmlspecialchars($request->input("filter"));
            $filteredWords = FilteredWord::where("word_type", $filter)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $filteredWords = FilteredWord::orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.filtered-words", compact(["filteredWords", "token", "keyword", "wordsByType", "filter"]));

    }

    public function postFilteredWordEdit($token, Request $request) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $word = Word::where("id", $request->input("wordId"))->first();
        if($word) {
            $word->update(["type" => $request->input("type")]);
        }

        $filteredWord = FilteredWord::where("id", $request->input("filteredWordId"))->first();
        if($filteredWord) {
            $filteredWord->update(["word_type" => $request->input("type")]);
        }

        return json_encode(["error" => false]);

    }

    public function postFilteredWordClear($token) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        if(FilteredWord::truncate())
            return json_encode(["error" => false]);
        else
            return json_encode(["error" => true]);

    }

    public function postFilteredWordRemove($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");

        if(!$this->isAuthorized($token) || !$id) {
            return response(["error" => true, "msg", "no permission"], 403);
        }

        $filteredWord = FilteredWord::find($id);
        if ($filteredWord) {
            $filteredWord->delete();
            return response(["error" => false, "msg", "success"], 200);
        } else {
            return response(["error" => true, "msg", "error"], 400);
        }

    }

    /* Subscriptions Related opations */
    public function getSubscriptions($token, Request $request) {

        $user = $this->isAuthorized($token, "admin_supers");
        if(!$user) return $this->renderNoPermissions();
        $users = [];
        $keyword = "";
        $filter = "";
        $_roles = $user->getRoleToUpgrade(true); // Set first paramter to true to get only supers.
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if($request->input("search")) {
            $keyword = htmlspecialchars($request->input("search"));
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", false)->where("ip", "like", "%".$keyword."%")
                ->orWhere("name", "like", "%".$keyword."%")->orWhere("decoration", "like", "%".$keyword."%")
                ->orWhere("device", "like", "%".$keyword."%")->orderBy("created_at", 'desc')->limit(1000)->get();
        } else if($request->input("filter")) {
            $filter = htmlspecialchars($request->input("filter"));
            $users = User::where("role_id", $filter)->where("is_virtual", false)->orderBy("created_at", 'desc')->limit(1000)->get();
        } else {
            $users = User::whereIn("role_id", $roleIDs)->where("is_virtual", false)->orderBy("created_at", 'desc')->limit(1000)->get();
        }

        return view("fadmin.pages.subscriptions", compact(["users", "token", "keyword", "filter", "_roles"]));

    }

    public function postUpdateSubscription($token, Request $request) {
        $user = $this->isAuthorized($token, "orders_upgrade");
        if(!$user) return $this->renderNoPermissions(true);

        $targetUser = User::find($request->input("userId"));
        if(!$targetUser) return json_encode(["error" => true, "msg" => "no super"]);

        $_roles = $user->getRoleToUpgrade();
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if(!in_array($targetUser->role_id, $roleIDs)) return $this->renderNoPermissions(true);

        $targetUser->subscription_end = \Carbon\Carbon::now()->addDays($request->input("days"));
        if($targetUser->save()) {
            return json_encode(["error" => false, "date" => $targetUser->subscriptionEnd()]);
        } else {
            return json_encode(["error" => true]);
        }
    }

    public function postUpdateSubscriptionPermanent($token, Request $request) {
        $user = $this->isAuthorized($token, "orders_upgrade");
        if(!$user) return $this->renderNoPermissions(true);

        $targetUser = User::find($request->input("userId"));
        if(!$targetUser) return json_encode(["error" => true, "msg" => "no super"]);

        $_roles = $user->getRoleToUpgrade();
        $roleIDs = [];
        foreach($_roles as $rl) { $roleIDs[] = $rl->id; }

        if(!in_array($targetUser->role_id, $roleIDs)) return $this->renderNoPermissions(true);

        $targetUser->permanent_subscription = $request->input("permanent") == "true" ? true : false;
        if($targetUser->save()) {
            $targetUser->checkSubscriptionExpiry();
            return json_encode(["error" => false, "date" => $targetUser->subscriptionEnd()]);
        } else {
            return json_encode(["error" => true]);
        }
    }

    /* Gifts and Faces Related opations */
    public function getGiftsAndFaces($token) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $gifts = Gift::all();
        $faces = Face::all();

        return view("fadmin.pages.gifts-and-faces", compact(["gifts", "faces", "token"]));
    }

    public function postGiftsFacesSaveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $type = $request->input("type");
        $faceKey = $request->input("key");
        if(!in_array($type, ['face', 'gift'])) return response(["error" => true, 'msg' => 'unknown type'], 200);

        if($type == "face") {
            if(!$faceKey) return json_encode(["error" => true, "msg" => "key is required"]);
            if(Face::where("key", $faceKey)->first()) return response(["error" => true, "msg" => "key is used"], 200);
        }

        $result = [];
        if(isset($_FILES["icon"]['tmp_name'])) {
            $tmp_name = $_FILES["icon"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["icon"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["icon"]['name']);
            end($array);
            $icon = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/'.$type.'s/'.$icon)) {

                if(str_contains(strtolower($icon), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/".$type."s/".$icon);
                }

                $obj = $type == "gift" ? Gift::create(["icon" => $icon]) : Face::create(["icon" => $icon, "key" => $faceKey]);
                if($obj) {
                    removeMaliciousFiles("uploads/".$type."s/");
                    return response(["error" => false, "message" => "success", "type" => $type, "icon" => $icon, 'id' => $obj->id]);
                } else {
                    removeMaliciousFiles("uploads/".$type."s/");
                    return response(["error" => true, "message" => "error"]);
                }

            } else {
                removeMaliciousFiles("uploads/".$type."s/");
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles("uploads/".$type."s/");
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function postGiftsFacesRemoveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $type = $request->input("type");
        $id = $request->input("id");
        if(!in_array($type, ['face', 'gift'])) return json_encode(["error" => true, 'msg' => 'unknown type']);

        $obj = $type == "gift" ? Gift::find($id) : Face::find($id);
        if($obj) {
            $oldIcon = $obj->icon;
            if($obj->delete()) {
                unlink("uploads/".$type."s/".$oldIcon);
                return response(["error" => false, "type" => $type]);
            } else {
                return response(["error" => true, "message" => "error"]);
            }

        } else {
            return response(["error" => true, "message" => "error"]);
        }

    }

    public function postUpdateFaceKey($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");
        $key = $request->input("key");

        $face = Face::find($id);

        if($face) {
            $face->key = htmlspecialchars($key);
            $face->save();
            return response(["error" => false, "message" => "success"]);
        } else {
            return response(["error" => true, "message" => "error"]);
        }

    }

    /* Button Backgrounds Related opations */
    public function getBtnBgs($token) {
        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $bgs = BtnBg::all();

        return view("fadmin.pages.btn-bgs", compact(["bgs", "token"]));
    }

    public function postBtnBgsSaveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["icon"]['tmp_name'])) {
            $tmp_name = $_FILES["icon"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["icon"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["icon"]['name']);
            end($array);
            $icon = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/btn-bgs/'.$icon)) {

                if(str_contains(strtolower($icon), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/btn-bgs/".$icon);
                }

                $obj = BtnBg::create(["icon" => $icon]);
                if($obj) {
                    removeMaliciousFiles("uploads/btn-bgs/");
                    return response(["error" => false, "message" => "success", "icon" => $icon, 'id' => $obj->id]);
                } else {
                    removeMaliciousFiles("uploads/btn-bgs/");
                    return response(["error" => true, "message" => "error"]);
                }

            } else {
                removeMaliciousFiles("uploads/btn-bgs/");
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles("uploads/btn-bgs/");
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function postBtnBgsRemoveIcon($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $id = $request->input("id");

        $obj = BtnBg::find($id);
        if($obj) {
            $oldIcon = $obj->icon;
            if($obj->delete()) {
                unlink("uploads/btn-bgs/".$oldIcon);
                return response(["error" => false]);
            } else {
                return response(["error" => true, "message" => "error"]);
            }

        } else {
            return response(["error" => true, "message" => "error"]);
        }

    }

    /* Shortcuts Related Options */
    public function getShortcuts($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions();

        $shortcuts = Shortcut::orderBy("created_at", "desc")->get();

        return view("fadmin.pages.shortcuts", compact(["shortcuts", "token"]));

    }

    public function postAddShortcut($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $this->validate($request, [
            'key'  => 'required|min:2',
            'value'   => 'required|min:2',
        ], [
            'key.required' => "الرجاء تحديد الإختصار",
            "key.min" => "الإختصار لا يجب أن يقل عن حرفين",
            "value.required" => "الرجاء تحديد الرسالة",
            "value.min" => "الرسالة لا يجب أن تقل عن حرفين",
        ]);

        if (count(Shortcut::where(['key' => $request->input('key')])->get()) === 0) {
            $shortcut = new Shortcut();
            $shortcut->key          = htmlentities($request->input('key'));
            $shortcut->value        = htmlentities($request->input('value'));
            $shortcut->save();

            return redirect()->back()->with("success_msg", "تم إنشاء الإختصار بنجاح")->with("shortcut", $shortcut->toJson());
        } else {
            return redirect()->back()->withErrors('هذا الإختصار موجود من قبل');
        }


    }

    public function postEditShortcut($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $ids        = $request->input('ids');
        $keys       = $request->input('keys');
        $values     = $request->input('values');
        $resultShortcuts = [];

        foreach ($ids as $key => $value) {
            try {

                $shortcut = Shortcut::find($value);

                if(!$shortcut) continue;

                $shortcut->key = htmlspecialchars($keys[$key]);
                $shortcut->value = htmlspecialchars($values[$key]);
                $shortcut->save();

                $resultShortcuts[] = $shortcut;

            } catch(\Exception $e) {}
        }

        if(count($resultShortcuts)) {
            return response(["error" => false, "message" => "shortcut(s) updated successfully", "shortcuts" => $resultShortcuts], 200);
        } else {
            return response(["error" => true, "message" => "no shortcut updated"], 200);
        }

    }

    public function postRemoveShortcut($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $results = [];
        $deleteds = [];
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            try {
                if($id == 1) continue;
                $shortcut = Shortcut::find($id);
                if(!$shortcut) continue;

                $shortcut->delete();
                $deleteds[] = $id;
            } catch(\Exception $e) {}
        }

        $results['error'] = false;
        $results['deleteds'] = $deleteds;
        return json_encode($results);
    }

    /* Self Messages Related Options */
    public function getSelfMessages($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions();

        $messages = SelfMessage::orderBy("created_at", "desc")->get();
        $settings = $this->settings;

        return view("fadmin.pages.self-messages", compact(["messages", "token", "settings"]));
    }

    public function postAddSelfMessage($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions();

        $this->validate($request, [
            "title" => "string|min:2",
            "message" => "string|min:2",
            "interval" => "numeric|min:1",
        ], [
            'title.string' => 'الرجاء تحديد عنوان الرسالة',
            'title.min' => 'عنوان الرسالة لا يجب أن يقل عن حرفين',
            'message.string' => 'الرجاء تحديد محتوى الرسالة',
            'message.min' => 'محتوى الرسالة لا يجب أن يقل عن حرفين',
            'interval.numeric' => 'الرجاء تحديد الفاصل الزمني',
            'interval.min' => 'الفاصل الزمني لا يجب أن يقل عن دقيقة'
        ]);

        $selfMessage = new SelfMessage();
        $selfMessage->title = htmlspecialchars($request->input("title"));
        $selfMessage->msg = htmlspecialchars($request->input("message"));
        $selfMessage->interval = $request->input("interval");
        $selfMessage->status = "running";
        $selfMessage->save();

        if($selfMessage) {
            return redirect()->back()->with("success_msg", "تم إضافة الرسالة بنجاح")->with("message", $selfMessage);
        } else {
            return redirect()->back()->withErrors('حدث خطأ أثناء القيام بالعملية');
        }

    }

    public function postEditSelfMessage($token, Request $request) {

        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $ids            = $request->input('ids');
        $titles         = $request->input('titles');
        $messages       = $request->input('messages');
        $intervals      = $request->input('intervals');
        $status         = $request->input('status');
        $resultMessages = [];

        foreach ($ids as $key => $value) {
            try {

                $msg = SelfMessage::find($value);

                if(!$msg) continue;

                $msg->title     = htmlspecialchars($titles[$key]);
                $msg->msg       = htmlspecialchars($messages[$key]);
                $msg->interval  = htmlspecialchars($intervals[$key]);
                $msg->status    = htmlspecialchars($status[$key]);
                $msg->save();

                $resultMessages[] = $msg;

            } catch(\Exception $e) {}
        }

        if(count($resultMessages)) {
            return response(["error" => false, "message" => "message(s) updated successfully", "messages" => $resultMessages], 200);
        } else {
            return response(["error" => true, "message" => "no message updated"], 200);
        }

    }

    public function postRemoveSelfMessage($token, Request $request) {
        $user = $this->isAuthorized($token, 'admin_settings');
        if(!$user) return $this->renderNoPermissions(true);

        $results = [];
        $deleteds = [];
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            try {

                $msg = SelfMessage::find($id);
                if(!$msg) continue;

                $msg->delete();
                $deleteds[] = $id;
            } catch(\Exception $e) {}
        }

        $results['error'] = false;
        $results['deleteds'] = $deleteds;
        return json_encode($results);
    }

    public function postSettingsChangeSelfMessagesIcon($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["flag"]['tmp_name'])) {
            $tmp_name = $_FILES["flag"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["flag"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["flag"]['name']);
            end($array);
            $flag = randomString(16).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/'.$flag)) {

                if(str_contains(strtolower($flag), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/".$flag);
                    removeMaliciousFiles('uploads/');
                    return response(["error" => true, "message" => "error_image_type"]);
                }

                if($this->settings->self_messages_icon) {
                    try {
                        unlink("uploads/" . $this->settings->self_messages_icon);
                    } catch(\Exception $e) {}
                }

                $this->settings->self_messages_icon = $flag;
                $this->settings->save();

                removeMaliciousFiles('uploads/');

                return response(["error" => false, "message" => "success", "icon" => $flag]);

            } else {
                removeMaliciousFiles('uploads/');
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles('uploads/');
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

    public function seflMessagesColors($token, Request $request) {

        if(!$this->isAuthorized( $token, 'admin_settings')) return $this->renderNoPermissions();

        $backgroundColor = htmlspecialchars($request->input("self_message_bg_color"));
        $btnColor        = htmlspecialchars($request->input("self_message_title_color"));
        $contentColor    = htmlspecialchars($request->input("self_message_content_color"));

        $this->settings->self_message_bg_color = !$backgroundColor || isXSS($backgroundColor) ? settingsGetDefault("self_message_bg_color") : $backgroundColor;
        $this->settings->self_message_title_color = !$btnColor || isXSS($btnColor) ? settingsGetDefault("self_message_title_color") : $btnColor;
        $this->settings->self_message_content_color = !$contentColor || isXSS($contentColor) ? settingsGetDefault("self_message_content_color") : $contentColor;
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    /* Settings Related opations */
    public function getSettings($token) {
        if(Session::has("success_mesg") && Session::get("success_mesg") == "تم إعادة تشيل الشات بنجاح") {
            Session::forget("success_mesg");
        } else {
            if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();
        }

        $settings = $this->settings;
        $rooms = Room::all();
        return view("fadmin.pages.settings", compact(["settings", "rooms", "token"]));

    }

    public function posSettingsSEO($token, Request $request) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $this->settings->chat_name        = htmlspecialchars($request->input('chat_name', ""));
        $this->settings->chat_title       = htmlspecialchars($request->input('chat_title', ""));
        $this->settings->chat_desc        = htmlspecialchars($request->input('chat_desc', ""));
        $this->settings->chat_keywords    = htmlspecialchars($request->input('chat_keywords', ""));
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);
    }

    public function posSettingsClearWall($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        Wall::truncate();
        foreach(glob(dirname(dirname(dirname(__DIR__))).'/public/uploads/wall/photos/*') as $file) {
            try { unlink($file); } catch (\Exception $e) {}
        }
        foreach(glob(dirname(dirname(dirname(__DIR__))).'/public/uploads/wall/sounds/*') as $file) {
            try { unlink($file); } catch (\Exception $e) {}
        }
        foreach(glob(dirname(dirname(dirname(__DIR__))).'/public/uploads/wall/videos/*') as $file) {
            try { unlink($file); } catch (\Exception $e) {}
        }

        return json_encode(["error" => false]);
    }

    public function posSettingsWall($token, Request $request) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $backgroundColor = htmlspecialchars($request->input("wall_banner_background_color"));
        $fontColor = htmlspecialchars($request->input("wall_banner_font_color"));

        $this->settings->wall_msg_count = htmlspecialchars($request->input('wall_msg_count', ""));
        $this->settings->wall_banner_msg = $request->input('wall_banner_msg', "");
        $this->settings->show_wall_banner = $request->has('show_wall_banner');

        $this->settings->wall_banner_background_color = !$backgroundColor || isXSS($backgroundColor) ? settingsGetDefault("wall_banner_background_color") : "#".$backgroundColor;
        $this->settings->wall_banner_font_color = !$fontColor || isXSS($fontColor) ? settingsGetDefault("wall_banner_font_color") : "#".$fontColor;
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    public function postSettingsChangeDefaultAvatar($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["avatar"]['tmp_name'])) {
            $tmp_name = $_FILES["avatar"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["avatar"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["avatar"]['name']);
            end($array);
            $avatar = randomString(16).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/avatars/'.$avatar)) {

                if(str_contains(strtolower($avatar), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("uploads/avatars/".$avatar);
                    removeMaliciousFiles('uploads/avatars/');
                    return response(["error" => true, "message" => "error_image_type"]);
                }

                if($this->settings->default_avatar) {
                    try {
                        unlink("uploads/avatars/" . $this->settings->default_avatar);
                    } catch(\Exception $e) {}
                }

                $this->settings->default_avatar = $avatar;
                $this->settings->save();

                removeMaliciousFiles('uploads/avatars/');

                return response(["error" => false, "message" => "success", "avatar" => $avatar]);

            } else {
                removeMaliciousFiles('uploads/avatars/');
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles('uploads/avatars/');
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

	public function postSettingsChangeBanner($token) {

		if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

		$result = [];
		if(isset($_FILES["banner"]['tmp_name'])) {
			$tmp_name = $_FILES["banner"]['tmp_name'];
			if(!$tmp_name) {
				$result['error'] = true;
				$result['message'] = 'please choose a file';
				return json_encode($result);
			}

			if(!isAllowedMimeType($tmp_name)) return;

			// if file extension is not allowed return
			$allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
			$filename = $_FILES["banner"]['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if(!in_array($ext, $allowed) ) return;

			// save file in the server
			$array = explode('.', $_FILES["banner"]['name']);
			end($array);
			$banner = randomString(16).'.'.$array[key($array)];
			if(move_uploaded_file($tmp_name, 'uploads/'.$banner)) {

				if(str_contains(strtolower($banner), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
					unlink("uploads/".$banner);
					removeMaliciousFiles('uploads/');
					return response(["error" => true, "message" => "error_image_type"]);
				}

				if($this->settings->banner_img) {
					try {
						unlink("uploads/" . $this->settings->banner_img);
					} catch(\Exception $e) {}
				}

				$oldBanner = $this->settings->banner_img;

				$this->settings->banner_img = $banner;
				$this->settings->save();

				removeMaliciousFiles('uploads/');

				return response(["error" => false, "message" => "success", "banner" => $banner, "oldBanner" => $oldBanner]);

			} else {
				removeMaliciousFiles('uploads/');
				return response(["error" => true, "message" => "error_saving_image"]);
			}
		} else {
			removeMaliciousFiles('uploads/');
			return response(["error" => true, "message" => "error_image_type"]);
		}

	}

	public function postSettingsChangeInterfaceBackground($token) {

		if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

		$result = [];
		if(isset($_FILES["background"]['tmp_name'])) {
			$tmp_name = $_FILES["background"]['tmp_name'];
			if(!$tmp_name) {
				$result['error'] = true;
				$result['message'] = 'please choose a file';
				return json_encode($result);
			}

			if(!isAllowedMimeType($tmp_name)) return;

			// if file extension is not allowed return
			$allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
			$filename = $_FILES["background"]['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if(!in_array($ext, $allowed) ) return;

			// save file in the server
			$array = explode('.', $_FILES["background"]['name']);
			end($array);
			$background = randomString(16).'.'.$array[key($array)];
			if(move_uploaded_file($tmp_name, 'uploads/'.$background)) {

				if(str_contains(strtolower($background), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
					unlink("uploads/".$background);
					removeMaliciousFiles('uploads/');
					return response(["error" => true, "message" => "error_image_type"]);
				}

				if($this->settings->interface_background) {
					try {
						unlink("uploads/" . $this->settings->interface_background);
					} catch(\Exception $e) {}
				}

				$oldBackground = $this->settings->interface_background;

				$this->settings->interface_background = $background;
				$this->settings->save();

				removeMaliciousFiles('uploads/');

				return response(["error" => false, "message" => "success", "background" => $background]);

			} else {
				removeMaliciousFiles('uploads/');
				return response(["error" => true, "message" => "error_saving_image"]);
			}
		} else {
			removeMaliciousFiles('uploads/');
			return response(["error" => true, "message" => "error_image_type"]);
		}

	}

	public function postSettingsChangeChatBackground($token) {

		if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

		$result = [];
		if(isset($_FILES["background"]['tmp_name'])) {
			$tmp_name = $_FILES["background"]['tmp_name'];
			if(!$tmp_name) {
				$result['error'] = true;
				$result['message'] = 'please choose a file';
				return json_encode($result);
			}

			if(!isAllowedMimeType($tmp_name)) return;

			// if file extension is not allowed return
			$allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
			$filename = $_FILES["background"]['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if(!in_array($ext, $allowed) ) return;

			// save file in the server
			$array = explode('.', $_FILES["background"]['name']);
			end($array);
			$background = randomString(16).'.'.$array[key($array)];
			if(move_uploaded_file($tmp_name, 'uploads/'.$background)) {

				if(str_contains(strtolower($background), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
					unlink("uploads/".$background);
					removeMaliciousFiles('uploads/');
					return response(["error" => true, "message" => "error_image_type"]);
				}

				if($this->settings->chat_background) {
					try {
						unlink("uploads/" . $this->settings->chat_background);
					} catch(\Exception $e) {}
				}

				$oldBackground = $this->settings->chat_background;

				$this->settings->chat_background = $background;
				$this->settings->save();

				removeMaliciousFiles('uploads/');

				return response(["error" => false, "message" => "success", "background" => $background]);

			} else {
				removeMaliciousFiles('uploads/');
				return response(["error" => true, "message" => "error_saving_image"]);
			}
		} else {
			removeMaliciousFiles('uploads/');
			return response(["error" => true, "message" => "error_image_type"]);
		}

	}

    public function postSettingsChangeBtnBg($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["file"]['tmp_name'])) {
            $tmp_name = $_FILES["file"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["file"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["file"]['name']);
            end($array);
            $image = randomString(16).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'images/'.$image)) {

                if(str_contains(strtolower($image), [".php", ".php2", ".rb", ".sh", ".py", "htaccess"])) {
                    unlink("images/".$image);
                    removeMaliciousFiles('images/');
                    return response(["error" => true, "message" => "error_image_type"]);
                }

                if($this->settings->btn_bg) {
                    try {
                        unlink("images/" . $this->settings->btn_bg);
                    } catch(\Exception $e) {}
                }

                $this->settings->btn_bg = $image;
                $this->settings->save();

                removeMaliciousFiles('images/');

                return response(["error" => false, "message" => "success", "image" => $image]);

            } else {
                removeMaliciousFiles('images/');
                return response(["error" => true, "message" => "error_saving_image"]);
            }
        } else {
            removeMaliciousFiles('images/');
            return response(["error" => true, "message" => "error_image_type"]);
        }

    }

	public function postSettingsRemoveBtnBg($token) {

		if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions(true);

		if($this->settings->btn_bg) {
			try {
				unlink("images/" . $this->settings->btn_bg);
			} catch(\Exception $e) {}
		}

		$this->settings->btn_bg = null;

		if($this->settings->save()) {
			return response(["error" => false, "message" => "success"]);
		} else {
			return response(["error" => true, "message" => "error_removing_image"]);
		}

	}

    public function posInterfaceettings($token, Request $request) {

        if(!$this->isAuthorized( $token, 'admin_settings')) return $this->renderNoPermissions();

        $backgroundColor = htmlspecialchars($request->input("background_color"));
        $btnColor        = htmlspecialchars($request->input("btn_color"));
        $windowColor     = htmlspecialchars($request->input("window_color"));
        $contentColor    = htmlspecialchars($request->input("content_color"));
        $borderColor     = htmlspecialchars($request->input("border_color"));

        $this->settings->background_color = !$backgroundColor || isXSS($backgroundColor) ? settingsGetDefault("background_color") : "#".$backgroundColor;
        $this->settings->btn_color = !$btnColor || isXSS($btnColor) ? settingsGetDefault("btn_color") : "#".$btnColor;
        $this->settings->window_color = !$windowColor || isXSS($windowColor) ? settingsGetDefault("window_color") : "#".$windowColor;
        $this->settings->content_color = !$contentColor || isXSS($contentColor) ? settingsGetDefault("content_color") : "#".$contentColor;
        $this->settings->border_color = !$borderColor || isXSS($borderColor) ? settingsGetDefault("border_color") : "#".$borderColor;
        $this->settings->show_banner = $request->has("show_banner");
        $this->settings->show_chat_background = $request->has("show_chat_background");
        $this->settings->show_chat_background_on_windows = $request->has("show_chat_background_on_windows");

        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    public function posMarqueSttings($token, Request $request) {

        if(!$this->isAuthorized( $token, 'admin_settings')) return $this->renderNoPermissions();

        $backgroundColor = htmlspecialchars($request->input("marquee_bg_color"));
        $textColor        = htmlspecialchars($request->input("marquee_text_color"));
        $content     = htmlspecialchars($request->input("marquee_content"));

        $this->settings->marquee_bg_color = !$backgroundColor || isXSS($backgroundColor) ? settingsGetDefault("marquee_bg_color") : "#".$backgroundColor;
        $this->settings->marquee_text_color = !$textColor || isXSS($textColor) ? settingsGetDefault("marquee_text_color") : "#".$textColor;
        $this->settings->marquee_content = !$content || isXSS($content) ? null : $content;
        $this->settings->disable_marquee = $request->has("disable_marquee");

        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    public function posSettingsVirtuals($token, Request $request) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $this->settings->virtuals_count = $request->input('virtuals_count', 20);
        $this->settings->virtuals_default_room = $request->input('virtuals_default_room', 1);
        $this->settings->disable_virtuals = $request->has('disable_virtuals');
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح");

    }

    public function posSettingsRestartChat($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        exec("sudo -u ".env("USERNAME")." forever restart ../server.js --max_old_space_size=2000", $output, $err);
        User::whereNotNull('adminToken')->update(['adminToken' => null]);

        return redirect()->back()->with("success_mesg", "تم إعادة تشيل الشات بنجاح");

    }

    public function posSettingsGeneral($token, Request $request) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

		$this->settings->required_likes_wall              = htmlspecialchars($request->input('required_likes_wall', 0));
		$this->settings->required_likes_notify            = htmlspecialchars($request->input('required_likes_notify', 0));
		$this->settings->required_likes_private           = htmlspecialchars($request->input('required_likes_private', 0));
        $this->settings->public_msg_length                = htmlspecialchars($request->input('public_msg_length', 150));
        $this->settings->private_msg_length               = htmlspecialchars($request->input('private_message_length', 75));
        $this->settings->time_between_messages            = htmlspecialchars($request->input('time_between_messages', 3));
        $this->settings->allow_multi_sessions             = $request->has('allow_multi_sessions');
		$this->settings->disable_signup                   = $request->has('disable_signup');
        $this->settings->disable_floating_particles       = $request->has('disable_floating_particles');
        $this->settings->disable_guest_login              = $request->has('disable_guest_login');
        $this->settings->background_color                 = htmlspecialchars($request->input('background_color', '#51515a'));
        $this->settings->disable_private_messages         = $request->has('disable_private_messages');
        $this->settings->disable_guest_private_messages   = $request->has('disable_guest_private_messages');
        $this->settings->disable_foreing_contries         = $request->has('disable_foreing_contries');
        $this->settings->bolcked_countries                = htmlspecialchars($request->input('bolcked_countries', ''));
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح")->with("reset_settings", true);

    }

    public function getDeveloperSettings($token) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $settings = $this->settings;
        return view("fadmin.pages.developer", compact(["settings", "token"]));

    }

    public function postDeveloperSettings($token, Request $request) {

        if(!$this->isAuthorized($token, 'admin_settings')) return $this->renderNoPermissions();

        $this->settings->css = $request->input('css');
        $this->settings->save();

        return redirect()->back()->with("success_mesg", "تم حفظ التعديلات بنجاح");
    }


    /************************************** Private Methods *************************************/
    private function isAuthorized($token, $key = null) {
        try {
            $user = User::where("adminToken", $token)->first();
            if(is_null($key)) return $user;
            return $user && $user->hasPermission($key) ? $user : false;
        } catch(\Exception $e) {
            return false;
        }
    }

    private function addRole($roleName) {
        $roleName = htmlspecialchars($roleName);
        $r = Role::create(["name" => $roleName]);
        if($r) {
            $privileges = Permission::where("role_id", $r->id)->first();
            if(!$privileges) Permission::create(["role_id" => $r->id]);
            return json_encode(["error" => false, "msg" => "success", "name" => $r->name, "id" => $r->id]);
        } else {
            return json_encode(["error" => true, "msg" => "error"]);
        }
    }

    private function removeRole($roleId) {
        $r = Role::where("id", $roleId)->first();
        if($r) {
            if(in_array($r->type, ["basic", "owner"]))
                return json_encode(["error" => true, "msg" => "unremovable role"]);

            $r = $r->delete();
            if($r) {
                Permission::where("role_id", $roleId)->delete();
                User::where("role_id", $roleId);
                return json_encode(["error" => false, "msg" => "success"]);
            } else {
                return json_encode(["error" => true, "msg" => "error"]);
            }
        } else {
            return json_encode(["error" => true, "msg" => "no super"]);
        }
    }

    private function renderNoPermissions($ajax = false) {
        if($ajax) return json_encode(["error" => true, "msg" => "not permitted"]);
        return "<h3 style='font-family: tahoma; color: red'>لا تملك الصلاحيات للقيام بهذه العملية</h3>";
    }

    public static function can($token, $key = null) {
        try {
            $user = User::where("adminToken", $token)->first();
            if(is_null($key)) return $user;
            return $user && $user->hasPermission($key) ? $user : false;
        } catch(\Exception $e) {
            return false;
        }
    }

}
