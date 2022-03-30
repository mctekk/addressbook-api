<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Social Login
 *
 */
Route::post('/oauth', [ 'as' => '/oauth', 'uses' => '\App\Http\Controllers\AuthController@socialLogin']);
Route::get('/auth/redirect', [ 'as' => '/auth/redirect', 'uses' => '\App\Http\Controllers\AuthController@socialRedirect']);

/**
 * Users endpoints
 */
Route::get('users', [\App\Http\Controllers\UsersController::class, 'index']);
Route::get('users/{id}', [\App\Http\Controllers\UsersController::class, 'show']);



//register new user
// Route::post('/signup', ['as' => 'signup' , 'uses' =>  '\App\Http\Controllers\AuthController@signup']);
// //login user
// Route::post('/login', ['as' => 'login', 'uses' =>  '\App\Http\Controllers\AuthController@login']);
// //using middleware
// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/profile', function (Request $request) {
//         return auth()->user();
//     });
//     Route::post('/logout', [AuthController::class, 'logout']);
// });
