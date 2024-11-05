<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(UserController::class)->group(function () {
    Route::get('/', 'user_list')->name('user_list');
    Route::post('/add_user', 'add_user')->name('add_user');
});
