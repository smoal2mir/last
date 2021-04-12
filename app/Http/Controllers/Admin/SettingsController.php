<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Option;
use App\Room;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller {

    public function __construct() {
        $this->option = Settings::findOrNew(1);
        $this->option->save();
    }

    public function index() {
        $rooms = Room::all();
        return view('admin.pages.settings', compact(["rooms"]))->withOptions($this->option);
    }

    public function postCredintials(Request $request) {
        if(!Auth::check()) return;

        $this->validate($request, [
            'username'  => 'required|min:2',
            'password'  => 'required|min:5',
        ], [
            'username.required' => 'الرجاء إدخال إسم المستخدم',
            'username.min' => 'إسم المستخدم لا يجب أن يقل عن حرفين',
            'password.required' => 'الرجاء إدخال كلمة المرور',
            'password.min' => 'كلمة المرور لا يجب أن تقبل عن 5 أحرف'
        ]);

        $user =  Auth::user();
        $user->name = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->back()->with("success_msg", "تم تغيير البيانات بنجاح");
    }

    public function posSettings(Request $request) {
        if(!Auth::check()) return;

        $this->option->required_likes_wall              = htmlspecialchars($request->input('required_likes_wall', 0));
        $this->option->required_likes_notify            = htmlspecialchars($request->input('required_likes_notify', 0));
        $this->option->required_likes_private           = htmlspecialchars($request->input('required_likes_private', 0));
        $this->option->public_msg_length                = htmlspecialchars($request->input('public_msg_length', 150));
        $this->option->private_msg_length               = htmlspecialchars($request->input('private_message_length', 75));
        $this->option->time_between_messages            = htmlspecialchars($request->input('time_between_messages', 3));
        $this->option->allow_multi_sessions             = $request->has('allow_multi_sessions');
        $this->option->disable_signup                   = $request->has('disable_signup');
        $this->option->disable_guest_login              = $request->has('disable_guest_login');
        $this->option->background_color                 = htmlspecialchars($request->input('background_color', '#51515a'));
        $this->option->disable_private_messages         = $request->has('disable_private_messages');
        $this->option->disable_guest_private_messages   = $request->has('disable_guest_private_messages');
        $this->option->disable_foreing_contries         = $request->has('disable_foreing_contries');
        $this->option->bolcked_countries                = htmlspecialchars($request->input('bolcked_countries', ''));
        $this->option->save();

        return redirect()->back()->with("success_msg", "تم تغيير الإعدادات بنجاح")->with("reload_settings", true);
    }

    public function posWallSettings(Request $request) {
        if(!Auth::check()) return;

        $this->validate($request, [
            'wall_msg_count'     => 'required|numeric|min:1|max:60'
        ], [
            'wall_msg_count.required' => 'الرجاء إدخال إسم المستخدم',
            'wall_msg_count.min' => 'إسم المستخدم لا يجب أن يقل عن حرفين',
            'wall_msg_count.max' => 'الرجاء إدخال كلمة المرور',
        ]);

        $this->option->wall_msg_count = $request->input('wall_msg_count', 25);
        $this->option->save();

        return redirect()->back()->with("success_msg", "تم التعديل بنجاح");
    }

    public function postVirtualSettings(Request $request) {
        if(!Auth::check()) return;
        $this->validate($request, [
            'action'            => 'required',
            'vu_count'          => 'required|numeric|min:1|max:100',
            'vu_room'           => 'required|numeric|min:1',
        ]);
        if($request->input('action') == 'postVirtualSettings-'.env("ADMIN_HASH")) {
            $this->option->vu_count         = $request->input('vu_count', 20);
            $this->option->vu_room          = $request->input('vu_room', 1);
            $this->option->disable_virtuals = $request->has('disable_virtuals');
            $this->option->save();
        }
        return redirect()->back();
    }

    public function postStartChat() {
        if(!Auth::check()) return;
        try {

            exec("sudo -u ".env("USERNAME")." forever start ../server.js --max_old_space_size=2000", $output, $err);
            // exec("screen -d -m node /home/vagrant/www/chats/yasschat/server.js", $output);
            foreach($output as $value) {
                // echo '<h3 style="margin: 0;">'.$value.'</h3>';
            }

            User::whereNotNull('FAdminToken')->update(['FAdminToken' => null]);

        } catch (\Exception $e) {}
    }

    public function postRestartChat() {
        if(!Auth::check()) return;
        try {

            exec("sudo -u ".env("USERNAME")." forever restart ../server.js --max_old_space_size=2000", $output, $err);
            // exec("screen -d -m node /home/vagrant/www/chats/yasschat/server.js", $output);
            foreach($output as $value) {
                // echo '<h3 style="margin: 0;">'.$value.'</h3>';
            }

            User::whereNotNull('FAdminToken')->update(['FAdminToken' => null]);

        } catch (\Exception $e) {}
    }

    public function postStopChat() {
        if(!Auth::check()) return;
        try {

            exec("sudo -u ".env("USERNAME")." forever stop ../server.js --max_old_space_size=2000", $output, $err);
            // exec("screen -d -m node /home/vagrant/www/chats/yasschat/server.js", $output);
            foreach($output as $value) {
                // echo '<h3 style="margin: 0;">'.$value.'</h3>';
            }

            User::whereNotNull('FAdminToken')->update(['FAdminToken' => null]);

        } catch (\Exception $e) {}
    }

}