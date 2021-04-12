<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wall extends Model {

    protected $table = 'wall';
    protected $fillable = ['type', 'body', 'upload_hash', 'user_id'];

}
