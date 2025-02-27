<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Exception;

/**
 * Custom API exception handler for managing various error responses.
 */
class ApiExceptionHandler
{
    public static function register($exceptions)
    {
        // Handle validation errors
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        });

        // Handle "resource not found" errors
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        });

        // Handle request throttling errors
        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            return response()->json([
                'message' => 'Too many requests',
            ], 429);
        });

        // Handle unexpected server errors
        $exceptions->render(function (Exception $e, $request) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        });
    }
}
