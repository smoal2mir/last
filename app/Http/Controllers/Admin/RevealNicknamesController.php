<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RevealNickname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RevealNicknamesController extends Controller {

    public function getAll(Request $request) {
        if(!Auth::check() || !Auth::user()->canRevealNicknames()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $keyword = "%".$query."%";
            $nicknames = RevealNickname::where("username", "LIKE", $keyword)->orWhere("ip", "LIKE", $keyword)
                ->orWhere("device", "LIKE", $keyword)->orWhere("decoration", "LIKE", $keyword)
                ->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $nicknames = RevealNickname::orderBy("created_at", "DESC")->paginate(100);
        }

        return view("admin.pages.reg.reveal-nicknames", compact(["nicknames", "query"]));
    }

    public function postEmpty(Request $request) {
        if(!Auth::check() || !Auth::user()->canRevealNicknames()) return response("Access Denied", 403);
        if($request->input('action') == 'emptyRevealNicknames-'.env("ADMIN_HASH")) {
            RevealNickname::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->canRevealNicknames()) return response("access denied", 301);
        if ($request->input('action') == 'deleteRevealNicknames-'.env("ADMIN_HASH")) {
            $results = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach ($ids as $id) {
                try {
                    $msg = RevealNickname::find($id);
                    if($msg) {
                        $msg->delete();
                        $deleteds[] = $id;
                    }
                } catch(\Exception $e) {}
            }
            $results['error'] = false;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        }
    }

}
