<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class FilteredWord extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    protected $table = 'filtered_words';
    protected $fillable = ['word_id', 'user', 'full_text', 'ip', 'word', 'word_type'];

    public function getWord() {
        try {
            return Word::find($this->word_id);
        } catch(\Exception $e) {
            return null;
        }
    }

}
