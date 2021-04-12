<?php

namespace App\Http\Controllers;

use App\Album;
use App\Permission;
use App\Photo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadController extends Controller {

	public function sendPrivateMedia(Request $request) {

		if(isset($_FILES['file']['tmp_name'])) {
			$tmp_name = $_FILES['file']['tmp_name'];
			if(!$tmp_name) {
				$result['error'] = true;
				$result['message'] = 'please choose a file1';
				return json_encode($result);
			}

			if(isMimeTypeBlocked($_FILES['file']['tmp_name'])) return;

			// if file extension is not allowed return
			$allowedVideos = ['mpeg', 'mpg', 'mp4', '3gp', 'ts1', 'mov'];
			$allowedSounds = ['mp3', 'wav', 'wave', 'audio', 'a4a', 'amr'];
			$allowedPhotos = ['gif', 'png', 'jpg', 'jpeg', 'bmp', 'png'];
			$filename = $_FILES['file']['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if(in_array($ext, $allowedVideos))
				$result['type'] = 'video';
			else if(in_array($ext, $allowedSounds))
				$result['type'] = 'sound';
			else if(in_array($ext, $allowedPhotos))
				$result['type'] = 'photo';
			else return json_encode(['error' => true, 'message' => 'file not allowed']);

			$privatePath = "uploads/private/";

			// Create directory if not exists
			if(!File::exists($privatePath))
				File::makeDirectory($privatePath, 0775, true);

			// save file in the server
			$array = explode('.', $_FILES['file']['name']);
			end($array);
			$file = randomString(32).'.'.$array[key($array)];
			if(move_uploaded_file($tmp_name, $privatePath.$file)) {

				$result['error'] = false;
				$result['message'] = 'avatar uploaded successfully';
				$result['file'] = $file;

				if(str_contains(strtolower($file), [".php", ".php2", ".rb", ".sh", ".py"])) {
					unlink($privatePath.$file);
				}

			} else {
				$result['error'] = true;
				$result['message'] = 'avatar not uploaded';
			}
		} else {
			$result['error'] = true;
			$result['message'] = 'please choose a file2';
		}

		removeMaliciousFiles($privatePath);

		return json_encode($result);

	}

    public function addPhotoToAlbum(Request $request) {

        $user = User::where("ajax_token", $request->input("token"))->first();
        if(!$user) return $this->renderNoPermissions(true);

        $result = [];
        if(isset($_FILES["photo"]['tmp_name'])) {
            $tmp_name = $_FILES["photo"]['tmp_name'];
            if(!$tmp_name) {
                return response(["error" => true, "message" => "please choose a file"]);
            }

            if(!isAllowedMimeType($_FILES["photo"]['tmp_name'])) return;

            // if file extension is not allowed return
            $allowed =  ['gif', 'png' ,'jpg', 'jpeg'];
            $filename = $_FILES["photo"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            $album = Album::where("user_id", $user->id)->first();
            if(!$album) {
                $album = Album::create(["user_id" => $user->id]);
            }

            $maxPhotos = Permission::maxAlbumPhotos($user->role_id);
            if(!$maxPhotos) return $this->renderNoPermissions(true);

            if($maxPhotos <= $album->photos->count()) {
                return response(["error" => true, "message" => "album is full", "max_photos" => $maxPhotos]);
            }

            if (!file_exists('uploads/albums/'.$album->id)) {
                mkdir('uploads/albums/'.$album->id, 0744, true);
            }

            // save file in the server
            $array = explode('.', $_FILES["photo"]['name']);
            end($array);
            $photo = randomString(10).'.'.$array[key($array)];
            if(move_uploaded_file($tmp_name, 'uploads/albums/'.$album->id.'/'.$photo)) {

                if(str_contains(strtolower($photo), [".php", ".php2", ".rb", ".sh", ".py", "pl", "htaccess"])) {
                    unlink("uploads/albums/".$album->id."/".$photo);
                }

                $photoObj = Photo::create(["src" => $photo, "album_id" => $album->id]);

                if($photoObj) {
                    removeMaliciousFiles("uploads/albums/".$album->id."/");
                    return response(["error" => false, "message" => "success", "src" => 'uploads/albums/'.$album->id.'/'.$photo, 'pid' => $photoObj]);
                } else {
                    removeMaliciousFiles("uploads/albums/".$album->id."/");
                    return response(["error" => true, "message" => "error"]);
                }

            } else {
                removeMaliciousFiles("uploads/albums/".$album->id."/");
                return response(["error" => true, "message" => "error"]);
            }
        } else {
            return response(["error" => true, "message" => "error"]);
        }


    }

    private function renderNoPermissions($ajax = false) {
        if($ajax) return response(["error" => true, "message" => "not permitted"]);
        return "<h3 style='font-family: tahoma; color: red'>لا تملك الصلاحيات للقيام بهذه العملية</h3>";
    }

}
