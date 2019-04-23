<?php

namespace App\Exceptions;

use App\Http\Constant\WebKeys;
use App\Util\Utils;
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
            if ($e instanceof ValidationException || $e instanceof InvalidArgumentException) {

                return response()->json(Utils::builder_error(WebKeys::STATUS_FIELD_ERROR, $e->getMessage(), $req->path(), $e->errors()));
            }
            else if ($e instanceof HttpException) {

                return response()->json(Utils::builder_error($e->getStatusCode(), $e->getMessage(), $req->path(), null));

            } else if ($e instanceof AuthenticationException) {

                return response()->json(Utils::builder_error(WebKeys::HTTP_UNAUTHORIZED, $e->getMessage(), $req->path(), null));

            } else if ($e instanceof TokenMismatchException) {

                return response()->json(Utils::builder_error(WebKeys::HTTP_FORBIDDEN,$e->getMessage() ? $e->getMessage() : 'CSRF Token Not Found', $req->path(), null));

            } else if ($e instanceof AuthorizationException) {

                return response()->json(Utils::builder_error(WebKeys::HTTP_FORBIDDEN, $e->getMessage() ? $e->getMessage() : 'Permission denied', $req->path(), null));

            } else {

                return response()->json(Utils::builder_error(WebKeys::HTTP_ERROR_SYSTEM, $e->getMessage() ? $e->getMessage() : 'Internal Error Server', $req->path(), null));
            }
        }
        return parent::render($req, $e);
    }

    protected function unauthenticated($req, AuthenticationException $e)
    {
        if ($req->isJson()) {

            return response()->json(Utils::builder_error(WebKeys::HTTP_UNAUTHORIZED,$e->getMessage() ? $e->getMessage() : 'Authorization Required', $req->path(), null));
        }
        return redirect()->guest($e->redirectTo() ?? route('login'));
    }
}
