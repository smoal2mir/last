<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OrderReg;
use Illuminate\Support\Facades\Auth;

class RegOrderController extends Controller {

    public function index(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return redirect()->back();

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $keyword = "%".$query."%";
            $orders = OrderReg::where("notes", "LIKE", $keyword)->orWhere("toRealName", "LIKE", $keyword)
                ->orWhere("toNickName", "LIKE", $keyword)->orWhere("byRealName", "LIKE", $keyword)
                ->orWhere("byNickName", "LIKE", $keyword)->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $orders = OrderReg::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.reg.orders', compact(["orders", "query"]));
    }

    public function postDelete(Request $request) {
        if(!Auth::check() || !Auth::user()->hasRegistryAccess()) return response("Access Denied", 403);
        if($request->input('action') == 'deleteRegOrders-'.env("ADMIN_HASH")) {
            $results  = [];
            $deleteds = [];
            $ids = $request->input('ids');
            foreach($ids as $id) {
                try {
                    OrderReg::find($id)->delete();
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
        if($request->input('action') == 'emptyRegOrders-'.env("ADMIN_HASH")) {
            OrderReg::truncate();
            return json_encode(["error" => false]);
        } else return response("Access Denied", 403);
    }

}