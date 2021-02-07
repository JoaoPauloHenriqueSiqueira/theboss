<?php

use App\Http\Controllers\LanguageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */


Auth::routes();

Route::get("/healthcheck", function () {
    return response()->json(["response" => 200]);
});

Route::get('/', 'PageController@home');
Route::post('/contact', 'ClientController@search')->name('contact');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/active', 'HomeController@active')->name('active')->middleware('check_company_already_active');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::post('/active', 'HomeController@activePost')->name('active_post');

    Route::group(['prefix' => 'admin', 'middleware' => 'check_active_company'], function () {

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/logout', 'AuthController@logout')->name('logout');

        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', 'SaleController@index')->name('sales');
            Route::get('/search', 'SaleController@index')->name('search_sales');
            Route::post('/search', 'SaleController@search');
            Route::post('/', 'SaleController@createOrUpdate')->name('make_sale');
            Route::delete('/', 'SaleController@delete')->name('delete_sales');;
        });

        Route::group(['prefix' => 'clients'], function () {
            Route::get('/', 'ClientController@index')->name('clients');
            Route::get('/search', 'ClientController@index');
            Route::post('/search', 'ClientController@search')->name('search_clients');
            Route::post('/', 'ClientController@createOrUpdate')->name('add_clients');
            Route::delete('/', 'ClientController@delete')->name('delete_clients');;
        });

        Route::group(['prefix' => 'products'], function () {

            Route::get('/', 'ProductController@index')->name('products');
            Route::get('/search', 'ProductController@index');
            Route::post('/search', 'ProductController@search')->name('search_products');
            Route::post('/', 'ProductController@createOrUpdate');
            Route::delete('/', 'ProductController@delete')->name('delete_products');
            Route::delete('/photo', 'ProductController@deletePhoto')->name('delete_product_photo');
            Route::post('/photo', 'ProductController@getPhotos')->name('get_product_photos');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('users');
            Route::get('/search', 'UserController@index');
            Route::post('/search', 'UserController@search')->name('search_users');
            Route::post('/', 'UserController@create');
            Route::put('/', 'UserController@update');
            Route::delete('/', 'UserController@delete');
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('categories');
            Route::get('/search', 'CategoryController@index');
            Route::post('/search', 'CategoryController@search')->name('search_categories');
            Route::post('/', 'CategoryController@createOrUpdate')->name('add_categories');
            Route::delete('/', 'CategoryController@delete')->name('delete_categories');;
        });

        Route::group(['prefix' => 'status'], function () {
            Route::get('/', 'StatusController@index')->name('status');
            Route::get('/search', 'StatusController@index');
            Route::post('/search', 'StatusController@search')->name('search_status');
            Route::post('/', 'StatusController@createOrUpdate')->name('add_status');
            Route::delete('/', 'StatusController@delete')->name('delete_status');;
        });

        Route::group(['prefix' => 'providers'], function () {
            Route::get('/', 'ProviderController@index')->name('providers');
            Route::get('/search', 'ProviderController@index');
            Route::post('/search', 'ProviderController@search')->name('search_providers');
            Route::post('/', 'ProviderController@createOrUpdate')->name('add_providers');
            Route::delete('/', 'ProviderController@delete')->name('delete_providers');;
        });

        Route::group(['prefix' => 'sizes'], function () {
            Route::get('/', 'SizeController@index')->name('sizes');
            Route::get('/search', 'SizeController@index');
            Route::post('/search', 'SizeController@search')->name('search_sizes');
            Route::post('/', 'SizeController@createOrUpdate')->name('add_sizes');
            Route::delete('/', 'SizeController@delete')->name('delete_sizes');;
        });
        
    });
});
