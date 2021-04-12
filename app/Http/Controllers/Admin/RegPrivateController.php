<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PrivateMsg;
use Illuminate\Support\Facades\Auth;

class RegPrivateController extends Controller {

    public function index() {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();
        $messages = PrivateMsg::orderBy("created_at", "DESC")->paginate(20);
        return view('admin.pages.reg.private', compact(["messages"]));
    }

    public function postSearch(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        $results = [];
        $keyword = "%".htmlentities($request->input("keyword"))."%";

        if($request->input('action') == 'searchPrivateMsg-'.env("ADMIN_HASH")) {

            $messages = PrivateMsg::where("msg", "LIKE", $keyword)->orWhere("toRealName", "LIKE", $keyword)
                ->orWhere("toNickName", "LIKE", $keyword)->orWhere("byRealName", "LIKE", $keyword)
                ->orWhere("byNickName", "LIKE", $keyword)->orderBy("created_at", "DESC")->limit(50)->get();

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
        if($request->input('action') == 'deleteRegPrivate-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    $msg = PrivateMsg::find($id);
                    if($msg) {
                        if($msg->msgType != 'text') {
                            $dir = dirname(dirname(dirname(__DIR__))).'/public/';
                            try {
                                unlink($dir.$msg->msg);
                            } catch (\Exception $e) {}
                        }
                        $msg->delete();
                        $deleteds[] = $id;
                    }
                } catch (\Exception $e) {}
            }
            $results['error'] = !count($deleteds) > 0;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        } else return response("Access Denied", 403);
    }

    public function postEmpty(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegPrivate-'.env("ADMIN_HASH")) {
            PrivateMsg::truncate();
            $dir = dirname(dirname(dirname(__DIR__))).'/public/uploads/private/*';
            foreach(glob($dir) as $file) {
                unlink($file);
            }
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

    public function postEmptyAttachments(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegPrivateAttach-'.env("ADMIN_HASH")) {
            $dir = dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/private/*';
            foreach(glob($dir) as $file) {
                unlink($file);
            }
            return json_encode(["error" => false]);
        }
    }

}