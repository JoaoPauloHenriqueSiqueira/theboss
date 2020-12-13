<?php

use Illuminate\Http\Request;

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

// Route::group([
//     'prefix' => 'auth'
//   ], function () {
//     Route::post('register','AuthController@register');
//     Route::post('login','AuthController@login');
//     Route::post('forgetpassword','AuthController@forgetPassword');

//     Route::group(['middleware' => 'auth:api'], function () {
//         Route::get('user','AuthController@user');
//         Route::get('logout','AuthController@logout');
//     });
// });

Route::group(['prefix' => 'admin', 'middleware' => 'check_api_token'], function () {
  Route::get('/categories', 'CategoryController@getList');
  Route::get('/products', 'ProductController@search');
  Route::get('/category/{id}/products', 'CategoryController@getProducts');

  Route::group(['prefix' => 'sales'], function () {
    Route::post('/', 'SaleController@createOrUpdateAPI');
  });
  
});
