<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ExcpelReg;
use Illuminate\Support\Facades\Auth;

class RegExcpelController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryExcpelAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $keyword = "%".$query."%";
            $excpels = ExcpelReg::where("toRealName", "LIKE", $keyword)
                ->orWhere("toNickName", "LIKE", $keyword)->orWhere("byRealName", "LIKE", $keyword)
                ->orWhere("byNickName", "LIKE", $keyword)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $excpels = ExcpelReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.excpels', compact(["excpels", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryExcpelAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegExcpels-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    ExcpelReg::find($id)->delete();
                    $deleteds[] = $id;
                } catch (\Exception $e) {}
            }
            $results['error'] = !count($deleteds) > 0;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        } else return response("Access Denied", 403);
    }

    public function postEmpty(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryExcpelAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegExcpels-'.env("ADMIN_HASH")) {
            ExcpelReg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}