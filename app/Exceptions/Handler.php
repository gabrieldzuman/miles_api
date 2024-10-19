<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Psr\Log\LogLevel;

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
     * Exceções listadas aqui não serão reportadas para os logs.
     * 
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        // 
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * Inputs sensíveis que nunca devem ser armazenados na sessão em casos de validação.
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
     * Registra callbacks para manipular exceções que podem ocorrer na aplicação.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof \App\Exceptions\CriticalException) {
                \Log::critical('Uma exceção crítica ocorreu: ' . $e->getMessage());
            }
        });
    }
}
