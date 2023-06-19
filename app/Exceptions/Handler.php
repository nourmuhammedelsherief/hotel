<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
//            return response()->json(['error' => 'Unauthenticated.'], 401);
            $errors = [
                'key'=>'token',
                'value'=>trans('messages.token_is_required'),
            ];

            http_response_code(401);  // set the code
            return response()->json($errors)->setStatusCode(401);

        }
//        $guard = array_get($exception->guards(),0);
        $guard = Arr::get($exception->guards(), 0);

        switch ($guard){
            default:
                $errors = [
                    'message'=>trans('messages.token_is_required'),
                ];

                http_response_code(401);  // set the code
                return response()->json($errors)->setStatusCode(401);
        }
        return redirect()->guest(route($login))->with('error', trans('messages.You_should_login_first'));
    }
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
