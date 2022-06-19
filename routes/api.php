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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/login', function (Request $request) {
//     return response()->json('you can not access without access token');
// });
// login & registration
Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});
// follow person & page
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'follow'
], function ($router) {
    Route::post('person/{id}', 'socialNetworkController@followPerson');
    Route::post('page/{id}', 'socialNetworkController@followPage');
});
// create page & post in a page
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'page'
], function ($router) {
    Route::post('create', 'socialNetworkController@pageCreate');
    Route::post('{pageId}/attach-post', 'socialNetworkController@pagePost');
});
// person post and feed
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'person'
], function ($router) {
Route::post('attach-post', 'socialNetworkController@personPost');
Route::get('feed', 'socialNetworkController@feed');
});