<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RoomReg;
use Illuminate\Support\Facades\Auth;

class RegRoomController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $keyword = "%".$query."%";
            $rooms = RoomReg::where("roomName", "LIKE", $keyword)->orWhere("byRealName", "LIKE", $keyword)
                ->orWhere("byNickName", "LIKE", $keyword)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $rooms = RoomReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.rooms', compact(["rooms", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegRooms-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    RoomReg::find($id)->delete();
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
        if($request->input('action') == 'emptyRegRooms-'.env("ADMIN_HASH")) {
            RoomReg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}