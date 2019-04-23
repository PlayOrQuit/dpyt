<?php


namespace App\Http\Constant;


class WebKeys
{
    const HTTP_OK = 200;
    const HTTP_ERROR_SYSTEM = 500;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;

    const STATUS_OK = 01;
    const STATUS_FIELD_ERROR = 02;
    const STATUS_DB = 03;
    const STATUS_ERROR = 04;

}