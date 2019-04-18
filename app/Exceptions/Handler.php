<?php

namespace App\Exceptions;

use App\Http\Constant\WebKeys;
use Exception;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($req, Exception $e)
    {
        if ($req->isJson()) {
            if ($e instanceof ValidationException) {
                $status_code = WebKeys::HTTP_BAD_REQUEST;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage();
                $res['error']['timestamp'] = now();
                $res['error']['field_errors'] = $e->errors();
                return response()->json($res, $status_code);
            } else if ($e instanceof InvalidArgumentException) {
                $status_code = WebKeys::HTTP_BAD_REQUEST;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage();
                $res['error']['timestamp'] = now();
                $res['error']['field_errors'] = $e->errors();
                return response()->json($res, $status_code);
            } else if ($e instanceof HttpException) {
                $status_code = $e->getStatusCode();
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage();
                $res['error']['timestamp'] = now();
                return response()->json($res, $status_code);
            } else if ($e instanceof AuthenticationException) {
                $status_code = WebKeys::HTTP_UNAUTHORIZED;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage();
                $res['error']['timestamp'] = now();
                return response()->json($res, $status_code);
            } else if ($e instanceof TokenMismatchException) {
                $status_code = WebKeys::HTTP_FORBIDDEN;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage() ? $e->getMessage() : "CSRF Token Not Found";
                $res['error']['timestamp'] = now();
                return response()->json($res, $status_code);
            }else if ($e instanceof AuthorizationException) {
                $status_code = WebKeys::HTTP_FORBIDDEN;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage() ? $e->getMessage() : "Permission denied";
                $res['error']['timestamp'] = now();
                return response()->json($res, $status_code);
            }
            else {
                $status_code = WebKeys::HTTP_ERROR_SYSTEM;
                $res['error']['status'] = $status_code;
                $res['error']['path'] = $req->path();
                $res['error']['message'] = $e->getMessage();
                $res['error']['timestamp'] = now();
                $res['error']['trace'] = $e->getTrace();
                return response()->json($res, $status_code);
            }
        }
        return parent::render($req, $e);
    }

    protected function unauthenticated($req, AuthenticationException $e)
    {
        if ($req->isJson()) {
            $status_code = WebKeys::HTTP_UNAUTHORIZED;
            $res['error']['status'] = $status_code;
            $res['error']['path'] = $req->path();
            $res['error']['message'] = $e->getMessage();
            $res['error']['timestamp'] = now();
            return response()->json($res, $status_code);
        }
        return redirect()->guest(route($e->redirectTo() ?? route('login')));
    }
}
