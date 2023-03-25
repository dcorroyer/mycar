<?php

namespace App\Exceptions;

use App\Exceptions\Authentication\InvalidAuthentication;
use App\Exceptions\User\InvalidUser;
use App\Exceptions\Vehicule\InvalidVehicule;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

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
    public function register(): void
    {
        $this->renderable(function (Exception $exception, Request $request) {
            if ($request->acceptsJson()) {
                if (in_array($exception::class, $this->getUnprocessableExceptions())) {
                    return response()->json(
                        ['message' => $exception->getMessage()],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }
            }
            return null;
        });
    }

    /**
     * Get classes who will return as "Unprocessable Entity" on JSON Request.
     *
     * @return array
     */
    private function getUnprocessableExceptions(): array
    {
        return [
            InvalidAuthentication::class,
            InvalidUser::class,
            InvalidVehicule::class,
        ];
    }
}
