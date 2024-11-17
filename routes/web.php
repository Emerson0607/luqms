<?php

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\PersonnelController;

Route::middleware('auth')->controller(ClientController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/client', 'getAllClients');
    Route::get('/get-oldest-client', 'getOldestClient');
    Route::get('/windows', 'getAllWindows');
    Route::get('/waitingQueue', 'waitingQueue');
    Route::get('/window', 'window');
    Route::get('/logs', 'logs');
});

Route::middleware('auth')->controller(PersonnelController::class)->group(function () {
    Route::get('/personnel', 'personnel')->name('personnel');
    Route::post('/personnel', 'p_store')->name('p_store');
    Route::delete('/personnel/{pId}','destroy')->name('personnel.destroy');
    Route::get('/homepage', 'homepage');
});

Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store');
    Route::post('/logout', 'destroy');
});