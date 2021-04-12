<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Wall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WallController extends Controller {

    public function postEmpty(Request $request) {

        if(!Auth::check()) return;
        Wall::truncate();

        foreach (glob(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/wall/photos/*') as $file) {
            try {
                unlink($file);
            } catch (\Exception $e) {
            }
        }
        foreach (glob(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/wall/sounds/*') as $file) {
            try {
                unlink($file);
            } catch (\Exception $e) {
            }
        }
        foreach (glob(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/wall/videos/*') as $file) {
            try {
                unlink($file);
            } catch (\Exception $e) {
            }
        }
        return json_encode(["error" => false]);
    }

}