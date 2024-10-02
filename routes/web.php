<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

// Route::get('/', function () {
//     return "Hello friends";
// });

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
])->group(function () {
    Route::get('/', function () {
        dd("give me a sample");
        return "Hello friends";
    });
});
