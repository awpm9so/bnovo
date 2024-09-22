<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Route::group([
//    'excluded_middleware' => 'api',
//    'prefix' => 'user'
//], function () {
//    Route::controller(UserController::class)->group(function () {
//        Route::get('/{id}', 'show')->name('user.show')->where(['id' => '[0-9]+']);
//        Route::post('/', 'store')->name('user.store');
//        Route::put('/{user}', 'update')->name('user.update')->where(['user' => '[0-9]+']);
//        Route::delete('/{id}', 'destroy')->name('user.destroy')->where(['id' => '[0-9]+']);
//    });
//});

Route::prefix('user')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/{id}', 'show')->name('user.show')->where(['id' => '[0-9]+']);
        Route::post('', action: 'store')->name('user.store');
        Route::put('/{id}', 'update')->name('user.update')->where(['id' => '[0-9]+']);
        Route::delete('/{id}', 'destroy')->name('user.destroy')->where(['id' => '[0-9]+']);
    });
});
