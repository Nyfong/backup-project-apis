<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // The homepage
});

// Show the login page
Route::get('/login', function () {
    return view('login'); // The login form
});

// Handle the login POST request
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']); // The login logic
