<?php


namespace App\Util;


use App\Http\Constant\WebKeys;

class Utils
{

    public static function builder_error($statusCode, $msg, $path, $field_errors)
    {
        $res['body']['statusCode'] = $statusCode;
        $res['body']['message'] = $msg != null ? $msg : '';
        $res['body']['path'] = $path != null ? $path : '';
        $res['body']['timestamp'] = now();
        if($field_errors != null)
            $res['body']['field_errors'] = $field_errors;
        return $res;
    }

    public static function builder_success($msg, $path, $data){
        $res['body']['statusCode'] = WebKeys::STATUS_OK;
        $res['body']['message'] = $msg != null ? $msg : '';
        $res['body']['path'] = $path != null ? $path : '';
        $res['body']['timestamp'] = now();
        if(is_array($res))
            $res['body']['data'] = $data;
        return $res;
    }

}