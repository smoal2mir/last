<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NotificationReg;
use Illuminate\Support\Facades\Auth;

class RegNotificationController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $q = "%".$query."%";
            $notifications = NotificationReg::where("msg", "LIKE", $q)->orWhere("toRealName", "LIKE", $q)
                ->orWhere("toNickName", "LIKE", $q)->orWhere("byRealName", "LIKE", $q)
                ->orWhere("byNickName", "LIKE", $q)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $notifications = NotificationReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.notifications', compact(["notifications", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegNotifications-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    NotificationReg::find($id)->delete();
                    $deleteds[] = $id;
                } catch (\Exception $e) {}
            }
            $results['error'] = !count($deleteds) > 0;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        } else return response("Access Denied", 403);
    }

    public function postEmpty(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegNotifications-'.env("ADMIN_HASH")) {
            NotificationReg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}