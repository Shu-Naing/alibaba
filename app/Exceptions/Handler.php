<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            $allowedMethods = $exception->getHeaders()['Allow'];

            $message = "Method not allowed. Allowed methods";

            $response = array();
            $response['status'] = '405';
            $response['description'] = $message;
    
            return new JsonResponse($response, Response::HTTP_METHOD_NOT_ALLOWED);
    
            throw new HttpResponseException(response($message, Response::HTTP_METHOD_NOT_ALLOWED));
        }else if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect('/');
        }
   
        return parent::render($request, $exception);
    }

    
}
?>