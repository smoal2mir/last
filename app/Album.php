<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ["user_id"];

	public function photos() {
		return $this->hasMany(Photo::class);
	}
}
