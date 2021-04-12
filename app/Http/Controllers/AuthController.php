<?php

namespace App\Http\Controllers;

use App\Banned;
use App\Http\Requests\ChangeUserPassword;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\GuestLoginRequest;
use App\Http\Requests\MemberLoginRequest;
use App\Role;
use App\Settings;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class AuthController extends Controller {

    private $settings;
    private $arabeCountries = [];

    function __construct() {
        $this->settings = Settings::first();
        $this->arabeCountries = ["dz", "eg", "sd", "iq", "ma", "sa", "ye", "sy", "tn", "so", "jo", "ae", "ly", "ps", "lb", "om", "kw", "mr", "qa", "bh", "dj", "tr"];
    }

    public function guestLogin(GuestLoginRequest $request) {

        $username = $request->input('name');
        $ip = $request->input("ip");
        $device = $request->input("device");

        $user = User::where("name", $username)->first();
        $banned = Banned::where("ip", $ip)->orWhere("device", $device)->first();

        if($banned) {

            return response(["error" => true, "is_friendly_message" => true, "message" => "لقد تم حظرك"], 400);

        } else if($user) {

            return response(["error" => true, "is_friendly_message" => true, "message" => "هدا الإسم محجوز, الرجاء إختيار إسم آخر"], 400);

        } else {

            $country = $request->input("country");

            try {
                if (!$country || !strlen($country) || $country == "undef") {
                    $country = strtolower(explode(";", $this->ipLookUpRequest($ip))[3]);
                }
            } catch (\Exception $e) { $country = "undef"; }

            if (!$this->isCountryAllowed($country, $this->arabeCountries, $this->settings->bolcked_countries, !!$this->settings->disable_foreing_contries)) {
                return response(["error" => true, "is_friendly_message" => true, "message" => "لقد تم حظرك"], 400);
            }

            return response(["error" => false, "message" => null], 200);

        }

    }

    public function memberLogin(MemberLoginRequest $request) {

        $test = null;

        $result = [];
        $name = $request->input('name');
        $password = $request->input('password');
        $ip = $request->input('ip');
        $device = $request->input('device');
        $hidden = $request->input('hidden');

        $user = User::where("name", $name)->first();
        $banned = Banned::where("ip", $ip)->orWhere("device", $device)->first();

        if ($banned) {

            return response(["error" => true, "banned_id" => $banned->id, "is_friendly_message" => true, "message" => "لقد تم حظرك"], 400);

        } else if ($user && Hash::check($password, $user->password)) {

            $result['name'] = $user->name;
            $result['hidden'] = $hidden;
            $result['decoration'] = $user->decoration;
            $result['id'] = $user->id;
            $result['ip'] = $ip;
            $result['role'] = $user->role_id;
            $result['gift'] = $user->gift;
            $result['fontSize'] = $user->fontSize;
            $result['fontColor'] = $user->fontColor;
            $result['statusColor'] = $user->statusColor;
            $result['nameColor'] = $user->nameColor;
            $result['nameBgColor'] = $user->nameBgColor;
            $result['reputation'] = $user->reputation;
            $result['status'] = $user->status;
            $result['avatar'] = $user->avatar;
            $result['isVirtual'] = $user->is_virtual;
            $result['defaultRoom'] = $user->default_room;
            $result['isPrivateDisabled'] = !!$user->isPrivateDisabled;
            $result['isRoleIconHidden'] = !!$user->isRoleIconHidden;

            if (isset($user->status) && strlen($user->status)) {
                $result["status"] = $user->status;
            } else {
                $result['status'] = Carbon::now()->subDay(3) > $user->created_at ? "(لا يوجد)" : "(عضو جديد)";
            }

            $result['orders_excpel'] = $user->orders_excpel;
            $result['orders_ban'] = $user->orders_ban;
            $result['orders_notify'] = $user->orders_notify;
            $result['orders_gift'] = $user->orders_gift;
            $result['orders_remove_avatar'] = $user->orders_remove_avatar;
            $result['orders_change_decoartion'] = $user->orders_change_decoartion;
            $result['orders_excpel_from_room'] = $user->orders_excpel_from_room;
            $result['room_create'] = $user->room_create;
            $result['room_edit'] = $user->room_edit;
            $result['room_remove'] = $user->room_remove;
            $result['room_max_pertinents'] = $user->room_max_pertinents;
            $result['send_ad'] = $user->send_ad;
            $result['wall_remove'] = $user->wall_remove;
            $result['wall_interval'] = $user->wall_interval;
            $result['public_msg_remove'] = $user->public_msg_remove;

            $adminToken = null;
            if ($user->hasFrontAdminAccess()) {
                $adminToken = str_random(24);
            }

            $result['adminToken'] = $adminToken;
            $country = $request->input("country");

            try {
                if (!$country || !strlen($country) || $country == "undef") {
                    $country = strtolower(explode(";", $this->ipLookUpRequest($request->input("ip")))[3]);
                }
            } catch (\Exception $e) { $country = "undef"; }

            if (!$this->isCountryAllowed($country, $this->arabeCountries, $this->settings->bolcked_countries, !!$this->settings->disable_foreing_contries)) {
                return response(["error" => true, "counry" => $country, "bolcked_countries" => $this->settings->bolcked_countries, "is_friendly_message" => true, "message" => "لقد تم حظر دولتك"], 400);
            }

            $result["country"] = $country;

            $user->adminToken = $adminToken;
            $user->ip = $ip;
            $user->device = $device;
            $user->country = $country;
            $user->last_login = Carbon::now();
            $user->save();

            return response(["error" => false, "user" => $result, "message" => "successfully signed in"], 200);

        } else {
            return response(["error" => true, "is_friendly_message" => true, "message" => "الرجاء التأكد من صحة البيانات"], 400);
        }

    }

    public function register(CreateUserRequest $request) {

        try {

            $banned = Banned::where("ip", $request->input("ip"))->orWhere("device", $request->input("device"))->first();

            if($banned) {
                return response(["error" => true, "banned_id" => $banned->id, "is_friendly_message" => true, "message" => "لقد تم حظرك"], 400);
            }

            $user = User::create([
                "name" => htmlspecialchars($request->input("name")),
                "password" => bcrypt($request->input("password")),
                "country" => htmlspecialchars($request->input("country")),
                "device" => htmlspecialchars($request->input("device")),
                "ip" => $request->input("ip"),
                "role_id" => Role::getRoleByType("basic")
            ]);

        } catch (Exception $e) {
            return response(["error" => true, "user" => null, "message" => $e->getMessage()], 400);
        }

        if($user) {
            return response(["error" => false, "user" => $user, "message" => "user created successfully"], 200);
        } else {
            return response(["error" => true, "user" => null, "message" => "failed to create user"], 400);
        }

    }

    public function changePassword(ChangeUserPassword $request) {
        $user = User::find($request->input("user_id"));
        $user->password = bcrypt($request->input("password"));
        $user->save();
        return response(["error" => false, "message" => "تمت العملية بنجاح"], 200);
    }

    private function ipLookUpRequest($ip) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.ipinfodb.com/v3/ip-country/?key=a06b3f9d1268e48fd2ee77dc06e923b2682b2d029d14cc8602101e082a621802&ip=$ip");
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        return $response;
    }

    private function isCountryAllowed($country, $arabeCountries, $blockedCountries, $disableForeingContries) {
        try {

            if($disableForeingContries && !in_array(strtolower($country), $arabeCountries)) return false;

            if($blockedCountries) {
                $blockedCountries = array_map('strtolower', array_filter(explode(PHP_EOL, $blockedCountries)));
                $blockedCountries2 = [];
                foreach ($blockedCountries as $cntr) {
                    $blockedCountries2[] = str_replace(["\n", "\t", "\r"], '', $cntr);;
                }
                if (in_array(strtolower($country), $blockedCountries2)) return false;
            }

            return true;

        } catch(\Exception $e) {
            return false;
        }
    }

}
