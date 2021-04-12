<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class RevealNickname extends Model {

    protected $table = 'reveal_names';
    protected $fillable = ["username", "ip", "role", "decoration", "device", "country"];

}