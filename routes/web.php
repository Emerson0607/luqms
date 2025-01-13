<?php

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\PersonnelController;

Route::middleware('auth')->controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/window', 'window');
    Route::get('/logs', 'logs');
    Route::post('/update-department', 'updateDepartment')->name('update-department');
});

Route::middleware('auth')->controller(PersonnelController::class)->group(function () {
    Route::get('/personnel', 'personnel')->name('personnel');
    Route::post('/personnel', 'p_store')->name('p_store');
    Route::delete('/personnel/{pId}','destroy')->name('personnel.destroy');
    Route::put('/personnel/{pId}', 'update');
    Route::get('/get-associated-services/{wName}/{deptId}', 'getAssociatedServices');

    // shared window
    Route::post('/sharedWindow', 'shared_store')->name('shared_store');
    Route::delete('/sharedWindow/{pId}','shared_destroy')->name('shared_destroy');
    Route::put('/sharedWindow/{pId}', 'shared_update');

    // for video charter
    Route::post('/charter1', 'charter1_store');
    Route::delete('/charter1/delete-video/{id}', 'deleteVideo')->name('charter.deleteVideo');

     // for video charter
     Route::post('/charter2', 'charter2_store');
     Route::delete('/charter2/delete-video2/{id}', 'deleteVideo2')->name('charter.deleteVideo2');
});

Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store');
    Route::post('/logout', 'destroy')->name('logout');
});
Route::get('/phpinfo', function () {
    phpinfo();
});
