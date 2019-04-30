<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Channel extends Model
{

    protected $hidden = [
        'refresh_token'
    ];

    protected $dates = [
        'iat',
    ];
//
//    public function setIatAttribute($iat){
//        Log::info($iat);
//        $date =  Carbon::createFromTimestamp($iat/1000);
//        Log::info($date->toDateString());
//        return $date;
//    }
}
