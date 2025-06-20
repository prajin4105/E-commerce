<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle model not found exceptions
        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                \Log::info('ModelNotFoundException handler triggered', [
                    'model' => $e->getModel(),
                    'url' => $request->fullUrl(),
                ]);
                $model = class_basename($e->getModel());
                $message = match ($model) {
                    'Subcategory' => 'Subcategory not found with this ID',
                    'Category' => 'Category not found with this ID',
                    default => 'Resource not found',
                };
                return response()->json([
                    'status' => 'error',
                    'message' => $message
                ], 404);
            }
        });

        // Handle not found exceptions
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                if (preg_match('/No query results for model \[(.*?)\]/', $e->getMessage(), $matches)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Resource not found'
                    ], 404);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not found'
                ], 404);
            }
        });

        // Handle authentication exceptions
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'error' => 'Please login to access this resource'
                ], 401);
            }
        });

        // Handle validation exceptions
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
        });

        // Handle all other exceptions for API routes
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if (app()->environment('production')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Server Error'
                    ], 500);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Server Error',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
                'error' => 'Please login to access this resource'
            ], 401);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Convert a not found exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function notFound($request, NotFoundHttpException $exception)
    {
        if ($request->is('api/*')) {
            $message = $exception->getMessage();
            if (preg_match('/No query results for model \[(.*?)\]/', $message, $matches)) {
                $modelName = class_basename($matches[1]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found'
                ], 404);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found'
            ], 404);
        }

        return response()->view('errors.404', [], 404);
    }
} 