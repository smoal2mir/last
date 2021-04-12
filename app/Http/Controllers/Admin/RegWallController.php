<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Wall;
use Illuminate\Http\Request;
use App\WallReg;
use Illuminate\Support\Facades\Auth;

class RegWallController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $q = '%'.$query.'%';
            $posts = WallReg::where("byRealName", "LIKE", $q)->orWhere("byNickName", "LIKE", $q)
                ->orWhere("msg", "LIKE", $q)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $posts = WallReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.wall', compact(["posts", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegWall-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    $msg = WallReg::find($id);
                    if($msg) {
                        if($msg->msgType != 'text') {
                            $dir = dirname(dirname(dirname(dirname(__DIR__)))).'/public/';
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
        if($request->input('action') == 'emptyRegWall-'.env("ADMIN_HASH")) {
            WallReg::truncate();
            Wall::truncate();
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/photos/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/sounds/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/videos/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

    public function postEmptyAttachments(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRegWallAttach-'.env("ADMIN_HASH")) {
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/photos/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/sounds/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            foreach(glob(dirname(dirname(dirname(dirname(__DIR__)))).'/public/uploads/wall/videos/*') as $file) {
                try { unlink($file); } catch (\Exception $e) {}
            }
            return json_encode(["error" => false]);
        }
    }

}