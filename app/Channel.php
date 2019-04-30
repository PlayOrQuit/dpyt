<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function data_token()
    {
        return $this->hasOne('App\DataToken');
    }
}
