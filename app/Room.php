<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Room extends Model {

	protected $table = "rooms";
	protected $fillable = ["name", "password", "capacity", "target", "default", "description", "welcome"];

    public function delete() {
        $this->deleteFlag();
        parent::delete();
    }

    public function deleteFlag() {
        if($this->flag) {
            try {
                File::deleteDirectory(public_path("uploads/rooms/$this->flag"));
            } catch(\Exception $e){}
        }
    }

}
