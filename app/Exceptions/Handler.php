<?php

namespace App\Exceptions;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    /**
     * @param Request $request
     * @param Exception|Throwable $e
     * @return JsonResponse|\Illuminate\Http\Response|Response
     * @throws Throwable
     */
    public function render($request, Exception|Throwable $e)
    {
        $message = $e->getMessage();
        $e = $this->prepareException($this->mapException($e));
        $code = match (get_class($e)) {
            ModelNotFoundException::class, => 404,
            MissingScopeException::class => 401,
            QueryException::class, TypeError::class => 500,
            ValidationException::class => $e->status,
            default => ($this->isHttpException($e)) ? $e->getStatusCode() : 500,
        };
        if (get_class($e) === ValidationException::class) $message = ['message' => $message, 'status' => false, 'data' => $e->validator->errors()];
        if (in_array($code, ["404", 404 ])) $message = 'Data not found !!!';

        if ($code == 500) {
            $message = 'Sorry something went wrong !!!';
            if (config('app.debug')) {
                $message = ['message' => $e->getMessage(), 'exception' => get_class($e), 'file' => $e->getFile()];
            } else {
                //sendSlackMessage()
            }
        }
        if ($request->expectsJson() || $request->is('api/*'))
            return (new Controller())->respondWithError($message, $code);
        return parent::render($request, $e);
    }

}
