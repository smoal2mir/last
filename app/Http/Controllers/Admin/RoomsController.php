<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Room;
use App\RoomReg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class RoomsController extends Controller {

    public function index(Request $request) {

        $query = $request->input("q");

        if($query && strlen(trim($query))) {
            $rooms = Room::where("name", "LIKE", "%".$query."%")->orderBy("created_at", "DESC")->paginate(100);
        } else {
            $rooms = Room::orderBy("created_at", "DESC")->paginate(100);
        }

        return view('admin.pages.rooms', compact('rooms', 'query'));
    }

    public function postAll(Request $request) {
        if($request->input('action') == 'getAllRooms-'.env("ADMIN_HASH")) {
            return Room::all();
        }
    }

    public function postAdd(Request $request) {
        if(!Auth::check()) return;
        $this->validate($request, [
            'name'  => 'required',
            'max'   => 'numeric|min:2|max:200',
            'for'   => 'in:all,supers',
        ]);
        if (count(Room::where(['name' => $request->input('name')])->get()) === 0) {
            $room = new Room();
            $room->name     = htmlentities($request->input('name'));
            $room->welcome  = htmlentities($request->input('welcome'));
            $room->password = htmlentities($request->input('password'));
            $room->capacity = htmlentities($request->input('max'));
            $room->target   = htmlentities($request->input('for'));
            $room->save();
            RoomReg::create([
                "byRealName"    => Auth::user()->name,
                "byNickName"    => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                "roomName"      => htmlentities($request->input('name')),
                "actionType"    => "add"
            ]);
            return redirect()->back()->with('addedRoom', $room);
        }

    }

    public function postDelete(Request $request) {
        if(!Auth::check()) return;
        if ($request->input('action') == 'deleteRoom-'.env("ADMIN_HASH")) {
            $results = [];
            $deleteds = [];
            $ids = $request->input('ids');

            foreach ($ids as $id) {
                try {
                    if($id == 1) continue;
                    $room = Room::findOrNew($id);

                    RoomReg::create([
                        "byRealName"    => Auth::user()->name,
                        "byNickName"    => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                        "roomName"      => $room->name,
                        "actionType"    => "remove"
                    ]);

                    $room->delete();
                    $deleteds[] = $id;
                } catch(\Exception $e) {}
            }

            $results['error'] = false;
            $results['deleteds'] = $deleteds;
            echo json_encode($results);
        }
    }

    public function postSave(Request $request) {
        if ($request->input('action') == 'saveChanges-'.env("ADMIN_HASH")) {
            $ids        = $request->input('ids');
            $names      = $request->input('names');
            $passwords  = $request->input('passwords');
            $welcomes   = $request->input('welcomes');
            $max        = $request->input('max');
            $fors       = $request->input('fors');
            $createds   = $request->input('createds');

            $results = [];
            $resultRooms = [];
            foreach ($ids as $key => $value) {
                try {
                    $room = Room::find($value);

                    $room->name = htmlentities($names[$key]);
                    $room->password = htmlentities($passwords[$key]);
                    $room->welcome = htmlentities($welcomes[$key]);
                    $room->capacity = (int)$max[$key];
                    $room->target = htmlentities($fors[$key]);
                    $room->created_at = htmlentities($createds[$key]);
                    $room->save();

                    $resultRooms[] = $room;

                    RoomReg::create([
                        "byRealName"    => Auth::user()->name,
                        "byNickName"    => Auth::user()->decoration ? Auth::user()->decoration : Auth::user()->name,
                        "roomName"      => $room->name,
                        "actionType"    => "edit"
                    ]);

                } catch(\Exception $e) {}
            }
            if(count($resultRooms)) {
                $results['error'] = false;
                $results['message'] = 'room(s) updated successfully';
                $results['rooms'] = $resultRooms;
            } else {
                $results['error'] = true;
                $results['message'] = 'no room updated';
            }
            echo json_encode($results);
        }
    }

    public function postChangeFlag() {

        $result = [];
        if(isset($_FILES["flag"]['tmp_name'])) {
            $tmp_name = $_FILES["flag"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file 1';
                return json_encode($result);
            }

            if(!isAllowedMimeType($tmp_name)) return;

            $oldFlag = basename($_POST['old_flag']); //room's previous flag to be removed
            $roomId = $_POST['room_id'];

            // if file extension is not allowed return
            $allowed =  array('gif', 'png' ,'jpg', 'jpeg');
            $filename = $_FILES["flag"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["flag"]['name']);
            end($array);
            $flag = $this->randomString(32).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/rooms/'.$flag)) {
                $result['error'] = false;
                $result['message'] = 'flag uploaded successfully';
                $result['flag'] = $flag;
                $result['id'] = $roomId;

                if($oldFlag != 'default.png' && File::exists('uploads/rooms/'.$oldFlag)) {
                    File::delete('uploads/rooms/'.$oldFlag);
                }

                $room = Room::find($roomId);
                if($room) {
                    $room->flag = $flag;
                    $room->save();
                }

                if(str_contains(strtolower($flag), [".php", ".php2", ".rb", ".sh", ".py"])) {
                    unlink("uploads/rooms/".$flag);
                }

            } else {
                $result['error'] = true;
                $result['message'] = 'flag not uploaded';
            }
        } else {
            $result['error'] = true;
            $result['message'] = 'please choose a file 2';
        }

        removeMaliciousFiles("uploads/rooms/");

        return json_encode($result);

    }

    private function randomString($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789_';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

}