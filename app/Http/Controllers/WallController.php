<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WallController extends Controller {

    public function uploadPhotoPost(Request $request, $allowed = ['gif','png' ,'jpg', 'jpeg'], $url = "uploads/wall/photos/", $type = 'photo') {
        $user = User::where("id", $request->input("id"))->where("upload_hash", $request->input("hash"))->first();
        if(!$user) return response(["error" => true, "message" => "invalid data"], 400);
        $result = [];

        if(isset($_FILES["file"]['tmp_name'])) {
            $tmp_name = $_FILES["file"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(isMimeTypeBlocked($_FILES['file']['tmp_name'])) return;

            // if file size is so long return
            $MAX_FILE_SIZE = 50000000;
            if($_FILES['file']['size'] > $MAX_FILE_SIZE) {
                $result['error'] = true;
                $result['message'] = $type.' so long';
                return json_encode($result);
            }

            // if file extension is not allowed return
            $filename = $_FILES['file']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed)) {
                $result['error'] = true;
                $result['message'] = 'invalid file';
                $result['ext'] = $ext;
                return json_encode($result);
            }

            // upload photo to server
            $array = explode('.', $_FILES['file']['name']);
            end($array);
            $photo = randomString(32).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, $url.$photo)) {

                $result['error'] = false;
                $result['type'] = $type;
                $result['body'] = $photo;

                $user->upload_hash = null;
                $user->save();

                if(str_contains(strtolower($photo), [".php", ".php2", ".rb", ".sh", ".py"])) {
                    unlink("uploads/wall/photos/".$photo);
                    unlink("uploads/wall/sounds/".$photo);
                    unlink("uploads/wall/videos/".$photo);
                }

            } else {
                $result['error'] = true;
                $result['message'] = 'post failure';
            }
        } else {
            $result['error'] = true;
            $result['message'] = 'please choose a file';
        }

        removeMaliciousFiles("uploads/wall/photos/");
        removeMaliciousFiles("uploads/wall/sounds/");
        removeMaliciousFiles("uploads/wall/videos/");

        return json_encode($result);
    }

    public function uploadVideoPost(Request $request) {
        return $this->uploadPhotoPost($request, ['3gp', 'mp4', 'mov', 'webm'], 'uploads/wall/videos/', 'video');
    }

    public function uploadSoundPost(Request $request) {
        return $this->uploadPhotoPost($request, ['mp3', 'm4a', 'audio', 'ogg', 'wav', '3ga', 'webm'], 'uploads/wall/sounds/', 'sound');
    }

}
