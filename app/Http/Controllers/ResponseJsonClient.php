<?php


namespace App\Http\Controllers;

use App\Http\Constant\WebKeys;
use App\Util\Utils;
use http\Client\Response;

trait ResponseJsonClient
{
    protected function _resJsonErr($msg, $path){
        return response()->json(Utils::builder_error(WebKeys::STATUS_ERROR, $msg, $path, null));
    }

    protected function _resJsonErrDB($msg, $path){
        return response()->json(Utils::builder_error(WebKeys::STATUS_DB, $msg, $path, null));
    }

    protected function _resJsonBad($msg, $path, $field_errors){
        return response()->json(Utils::builder_error(WebKeys::STATUS_FIELD_ERROR, $msg, $path, $field_errors));
    }

    protected function _resJsonSuccess($msg, $path, $data){
        return response()->json(Utils::builder_success($msg, $path, $data));
    }

    protected function _resJsonKeyNotFound($msg, $path){
        return response()->json(Utils::builder_error(WebKeys::STATUS_API_KEY_NOT_FOUND, $msg, $path, null));
    }
}