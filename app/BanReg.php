<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class BanReg extends Model {

    protected $table = 'bansReg';
    protected $fillable = ["byRealName", "byNickName", "toRealName", "toNickName", "reason", "actionType"];

    public function getReason() {
        return $this->reason && strlen($this->reason) ? $this->reason : "لم يتم ذكر السبب";
    }

}