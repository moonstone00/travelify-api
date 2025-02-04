<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// group route with prefix "admin"
Route::prefix('admin')->group(function () {
    // route login
    Route::post('/login', App\Http\Controllers\Api\Admin\LoginController::class, ['as' => 'admin']);

    // group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {

        // route user logged in
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user');

        // route logout
        Route::post('/logout', App\Http\Controllers\Api\Admin\LogoutController::class, ['as' => 'admin']);

        // route dashboard
        Route::get('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class, ['as' => 'admin']);

        // categories resource
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);

        // places resource
        Route::apiResource('/places', App\Http\Controllers\Api\Admin\PlaceController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);

        // sliders resource
        Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class, ['except' => ['create', 'edit', 'show', 'update'], 'as' => 'admin']);

        // users resource
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
    });
});

Route::prefix('web')->group(function() {

    // route categories index
    Route::get('/categories', [App\Http\Controllers\Api\Web\CategoryController::class, 'index', ['as' => 'web']]);

    // route categories show
    Route::get('/categories/{slug?}', [App\Http\Controllers\Api\Web\CategoryController::class, 'show', ['as' => 'web']]);

    // Route Places Index
    Route::get('/places', [App\Http\Controllers\Api\Web\PlaceController::class, 'index', ['as' => 'web']]);

    // Route Places Show
    Route::get('/places/{slug?}', [App\Http\Controllers\Api\Web\PlaceController::class, 'show', ['as' => 'web']]);

    // Route All Places Index
    Route::get('/all_places', [App\Http\Controllers\Api\Web\PlaceController::class, 'all_places', ['as' => 'web']]);

});
