<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Validation error.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException
                    ? $e->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

                return response()->json([
                    'status'  => $status,
                    'message' => $e->getMessage(),
                ], $status);
            }
        });
    })
    ->create();
