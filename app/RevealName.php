<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class RevealName extends Model {

    protected $table = 'reveal_names';
    protected $fillable = ["username", "ip", "role", "decoration", "device", "country"];

}