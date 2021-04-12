<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BanReg;
use Illuminate\Support\Facades\Auth;

class RegBanController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryBannedsAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $q = "%".$query."%";
            $bans = BanReg::where("reason", "LIKE", $q)->orWhere("toRealName", "LIKE", $q)
                ->orWhere("toNickName", "LIKE", $q)->orWhere("byRealName", "LIKE", $q)
                ->orWhere("byNickName", "LIKE", $q)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $bans = BanReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.bans', compact(["bans", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryBannedsAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegBans-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    BanReg::find($id)->delete();
                    $deleteds[] = $id;
                } catch (\Exception $e) {}
            }
            $results['error'] = !count($deleteds) > 0;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        } else return response("Access Denied", 403);
    }

    public function postEmpty(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryBannedsAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegBans-'.env("ADMIN_HASH")) {
            BanReg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}