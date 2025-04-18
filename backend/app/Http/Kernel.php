<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\CorsMiddleware;

class Kernel extends HttpKernel
{
    protected $middleware = [
        CorsMiddleware::class,
    ];
}
