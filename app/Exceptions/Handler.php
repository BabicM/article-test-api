<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        switch ($e) {
            case $e instanceof ValidationException:
                return $this->errorResponse(422, 'Wrong validation.', $e->validator->getMessageBag()->toArray());
            case $e instanceof NotFoundHttpException:
                return $this->errorResponse(404, $e->getMessage());
            default:
                return $this->errorResponse(400, $e->getMessage());
        }
    }

    protected function errorResponse(int $code, ?string $message = null, ?array $data = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }
}
