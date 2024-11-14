<?php

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisteredUserController;

// Route to display the clients (home page)
Route::get('/', [ClientController::class, 'index'])->middleware('auth');

Route::middleware('auth')->controller(ClientController::class)->group(function () {
    Route::get('/client', 'getAllClients');
    Route::get('/get-oldest-client', 'getOldestClient');
    Route::get('/windows', 'getAllWindows');
    Route::get('/waitingQueue', 'waitingQueue');
    Route::get('/window', 'window');
    // Route::get('/user', 'user');
    // Route::post('/user', 'store');
    // Route::put('/user/{user}', 'update');
    // Route::delete('/user/{user}','destroy');

});

Route::get('/homepage', [ClientController::class, 'homepage']);


Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store');
    Route::post('/logout', 'destroy');
});

