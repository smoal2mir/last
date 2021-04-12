<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class SelfMessage extends Model {

    protected $table = 'self_messages';
    protected $fillable = ["title", "msg", "interval", "running"];

}