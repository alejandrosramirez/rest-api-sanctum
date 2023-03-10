<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException as SpatieUnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e): void {
        });
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        if (!env('APP_ENABLE_CUSTOM_HANDLER')) {
            return parent::render($request, $e);
        }

        if ($e instanceof DefaultException) {
            return $this->customErrorResponse(
                'default_error',
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->customErrorResponse(
                'authentication_error',
                __('Unauthenticated.'),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (
            $e instanceof UnauthorizedException ||
            $e instanceof AuthorizationException ||
            $e instanceof SpatieUnauthorizedException
        ) {
            return $this->customErrorResponse(
                'unauthorized_error',
                __('You don\'t have the right permissions to access the resource.'),
                Response::HTTP_FORBIDDEN
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->customErrorResponse(
                'model_error',
                __(':model not found.', ['model' => __(class_basename($e->getModel()))]),
                Response::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof NotFoundHttpException || $e instanceof RouteNotFoundException) {
            return $this->customErrorResponse(
                'not_found_http_error',
                __('Url doesn\'t exist.'),
                Response::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->customErrorResponse(
                'method_not_allowed_error',
                __('Specified method on request not valid.'),
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($e instanceof NotAcceptableHttpException) {
            return $this->customErrorResponse(
                'not_acceptable_http_error',
                __('Specified accept header on request not valid.'),
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        if ($e instanceof QueryException) {
            return $this->customErrorResponse(
                'query_error',
                __('A error ocurred, cannot process query.'),
                Response::HTTP_CONFLICT
            );
        }

        if ($e instanceof PostTooLargeException) {
            return $this->customErrorResponse(
                'post_too_large_error',
                __('Request body is too large.'),
                Response::HTTP_REQUEST_ENTITY_TOO_LARGE
            );
        }

        if ($e instanceof UnsupportedMediaTypeHttpException) {
            return $this->customErrorResponse(
                'unsupported_media_type_http_error',
                __('Specified media type on request not valid.'),
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        if ($e instanceof ValidationException) {
            return $this->customErrorResponse(
                'validation_error',
                $e->validator->errors()->getMessages(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($e instanceof ThrottleRequestsException) {
            return $this->customErrorResponse(
                'throttle_requests_error',
                __('Too many attempts. Please try again later.'),
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        return $this->customErrorResponse(
            'internal_server_error',
            __('A server error has ocurred.'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Print custom error response.
     *
     * @param  string  $type
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse
     */
    private function customErrorResponse(string $error, string|object|array $message, int $statusCode)
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }
}
