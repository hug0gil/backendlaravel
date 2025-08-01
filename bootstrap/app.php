<?php

use App\Http\Middleware\CheckValueInHeader;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //$middleware->append(CheckValueInHeader::class);
        // Middleware global
        $middleware->alias(["checkvalue" => CheckValueInHeader::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command("maintenance:clear-old-uploads")->everyMinute();
    })->create();
