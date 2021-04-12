<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class RoomReg extends Model {

    protected $table = 'roomsReg';
    protected $fillable = ["byRealName", "byNickName", "roomName", "notes", "actionType"];

}