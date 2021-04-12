<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class GuestController extends Controller {

    public function changeAvatar(Request $request) {
        $result = [];
        if(isset($_FILES["avatar"]['tmp_name'])) {
            $tmp_name = $_FILES["avatar"]['tmp_name'];
            if(!$tmp_name) {
                $result['error'] = true;
                $result['message'] = 'please choose a file';
                return json_encode($result);
            }

            if(isMimeTypeBlocked($_FILES["avatar"]['tmp_name'])) return;

            // if file extension is not allowed return
            $allowed =  array('gif', 'png' ,'jpg', 'jpeg');
            $filename = $_FILES["avatar"]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed) ) return;

            // save file in the server
            $array = explode('.', $_FILES["avatar"]['name']);
            end($array);

            try {

                $image = Input::file('avatar');
                $avatar = str_random(32) . '.' . $image->getClientOriginalExtension();
                $smallPath   = public_path() . '/uploads/avatars/sm/';
                $largePath   = public_path() . '/uploads/avatars/lg/';

                Image::configure(array('driver' => 'gd'));

                if(strtolower($ext) == "gif") {

                    if(move_uploaded_file($tmp_name, 'uploads/avatars/sm/'.$avatar)) {
                        $result['error'] = false;
                        $result['message'] = 'avatar uploaded successfully';
                        $result['avatar'] = $avatar;

                        File::copy('uploads/avatars/sm/'.$avatar, 'uploads/avatars/lg/'.$avatar);

                        if(str_contains(strtolower($avatar), [".php", ".php2", ".rb", ".sh", ".py"])) {
                            unlink("uploads/avatars/".$avatar);
                        }

                    } else {
                        $result['error'] = true;
                        $result['message'] = 'avatar not uploaded';
                    }

                } else {

                    $iImage = Image::make($image->getRealPath())
                        ->resize(null, 50, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($smallPath . $avatar);

                    $iImage = Image::make($image->getRealPath())
                        ->resize(null, 200, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($largePath . $avatar);

                }

                removeMaliciousFiles('uploads/avatars/sm/');
                removeMaliciousFiles('uploads/avatars/lg/');

                $result['error'] = false;
                $result['ext'] = $ext;
                $result['message'] = 'avatar uploaded successfully';
                $result['avatar'] = $avatar;

            } catch (\Exception $e) {
                removeMaliciousFiles('uploads/avatars/sm/');
                removeMaliciousFiles('uploads/avatars/lg/');
                $result['error'] = true;
                $result['message'] = 'avatar not uploaded';
            }

        } else {
            $result['error'] = true;
            $result['message'] = 'please choose a file';
        }

        removeMaliciousFiles('uploads/avatars/sm/');
        removeMaliciousFiles('uploads/avatars/lg/');

        return json_encode($result);
    }

}
