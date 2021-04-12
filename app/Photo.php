<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ["album_id", "src"];

	public function album() {
		return $this->hasOne(Album::class);
	}

}
