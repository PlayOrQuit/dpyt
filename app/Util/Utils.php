<?php


namespace App\Util;


class Utils
{
    public static function builder_error($status_code = 500, $msg = null, $path = null, $field_errors = null, $timestamp = null){
        $data['result']['statusCode'] = $status_code;
        $data['result']['message'] = $msg != null ? $msg : '';
        $data['result']['path'] = $path != null ? $path : '';
        $data['result']['timestamp'] = $timestamp != null ? $timestamp : now();
        if($field_errors != null)
            $data['result']['field_errors'] = $field_errors;
        return $data;
    }
    public static function builder_success($status_code = 200, $data = null, $msg = null, $path = null, $timestamp = null){
        $data['result']['statusCode'] = $status_code;
        $data['result']['message'] = $msg != null ? $msg : '';
        $data['result']['path'] = $path != null ? $path : '';
        $data['result']['timestamp'] = $timestamp != null ? $timestamp : now();
        if($data != null)
            $data['result']['data'] = $data;
        return $data;
    }

}