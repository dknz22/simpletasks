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
        // Handle validation errors with improved readability
        $exceptions->render(function (ValidationException $e, $request) {
            $errors = [];

            foreach ($e->errors() as $field => $messages) {
                $cleanField = preg_replace('/\.\d+$/', '', $field);
                if (!isset($errors[$cleanField])) {
                    $errors[$cleanField] = [];
                }
                $errors[$cleanField] = array_merge($errors[$cleanField], $messages);
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
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
