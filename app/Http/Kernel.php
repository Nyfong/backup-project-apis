<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
  // In app/Http/Kernel.php, add a custom middleware if you don't have one already
protected $routeMiddleware = [
    // Other middlewares
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];

}
