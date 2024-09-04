<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e) {
            return $this->notFoundResponse('Resource not found.');
        });

        $exceptions->render(function (ValidationException $e) {
            return $this->formValidationErrorResponse($e->errors());
        });

        $exceptions->render(function (AuthenticationException $e) {
            return $this->badRequestResponse($e->getMessage());
        });

        $exceptions->render(function (UnauthorizedException $e) {
            return $this->badRequestResponse($e->getMessage());
        });
    })->create();
