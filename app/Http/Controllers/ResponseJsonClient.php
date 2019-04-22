<?php


namespace App\Http\Controllers;

use App\Http\Constant\WebKeys;
use App\Util\Utils;

trait ResponseJsonClient
{
    protected function _resJsonError($status_code, $msg, $field_errors, $path){
        return response()->json(Utils::builder_error($status_code, $msg, $path, $field_errors), WebKeys::HTTP_OK);
    }

    protected function _resJsonSuccess($status_code, $data, $msg, $path){
        return response()->json(Utils::builder_success($status_code, $data, $msg, $path), WebKeys::HTTP_OK);
    }
}