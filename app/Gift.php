<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model {

    protected $table = 'gifts';
    protected $fillable = ['icon'];
    
}
