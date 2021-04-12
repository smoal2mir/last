<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PublicMsg;
use Illuminate\Support\Facades\Auth;

class RegPublicController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $q = "%".$query."%";
            $messages = PublicMsg::where("byRealName", "LIKE", $q)->orWhere("byNickName", "LIKE", $q)
                ->orWhere("msg", "LIKE", $q)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $messages = PublicMsg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.public', compact(["messages", "query"]));
    }

    public function postSearch(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        $results = [];
        $keyword = "%".htmlentities($request->input("keyword"))."%";

        if($request->input('action') == 'searchPublicMsg-'.env("ADMIN_HASH")) {

            $messages = PublicMsg::where("msg", "LIKE", $keyword)->orWhere("byRealName", "LIKE", $keyword)
                ->orWhere("byNickName", "LIKE", $keyword)->orderBy("created_at", "DESC")->limit(1000)->get();

            $results['error'] = false;
            $results['messages'] = $messages;
            $results['length'] = count($messages->toJson());

            echo json_encode($results);

        } else {
            return response("Access Denied", 403);
        }
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegPublic-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    PublicMsg::find($id)->delete();
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
        if($request->input('action') == 'emptyRegPublic-'.env("ADMIN_HASH")) {
            PublicMsg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}