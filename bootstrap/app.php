<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $redirectOnExpiredSession = function (Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }

            if ($request->is('login') || $request->is('logout')) {
                return redirect()->route('login')
                    ->with('status', 'Sesi halaman diperbarui, silakan lanjutkan login.');
            }

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('error', 'Sesi form kedaluwarsa, silakan coba kirim ulang.');
        };

        $exceptions->render(fn (TokenMismatchException $e, Request $request) => $redirectOnExpiredSession($request));

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) use ($redirectOnExpiredSession) {
            return $e->getStatusCode() === 419 ? $redirectOnExpiredSession($request) : null;
        });
    })->create();
