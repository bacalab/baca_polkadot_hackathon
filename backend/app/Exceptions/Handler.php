<?php

namespace App\Exceptions;

use App\Libs\Output;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\Exception\OAuthServerException;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof OAuthServerException) {
            logger('unAuthorization', [
                'uid' => auth()->id(),
                'ip' => request()->getClientIp(),
                'url' => request()->getRequestUri(),
                'param' => request()->all(),
                'header' => request()->header()
            ]);
            return;
        }
        logger()->emergency('requestError', [
            'errCode' => $exception->getCode(),
            'msg' => $exception->getMessage() . ' file:' . $exception->getFile() . '(' . $exception->getLine() . ')',
            'uid' => Auth::id(),
            'ip' => request()->getClientIp(),
            'url' => request()->getRequestUri(),
            'param' => request()->all(),
            'header' => request()->header()
        ]);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(Output::ret(Output::AUTH_FAILED_LOGIN));
        }
    }
}
