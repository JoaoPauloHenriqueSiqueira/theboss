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

Route::get('/', function () {
    return response('Hello World', 200)
        ->header('Content-Type', 'text/plain');
});

Auth::routes();

Route::post('/contact', 'ClientController@search')->name('contact');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/active', 'HomeController@active')->name('active')->middleware('check_company_already_active');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::post('/active', 'HomeController@activePost')->name('active_post');
    Route::get('/payment', 'HomeController@payment')->name('payment');
    Route::post('/create-preference', 'HomeController@createPayment')->name('create-preference');

    Route::group(['prefix' => 'admin', 'middleware' => ['check_active_company','check_payment']], function () {

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/logout', 'AuthController@logout')->name('logout');

        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', 'SaleController@index')->name('sales');
            Route::get('/search', 'SaleController@index')->name('search_sales');
            Route::post('/search', 'SaleController@search');
            Route::post('/', 'SaleController@createOrUpdate')->name('make_sale');
            Route::delete('/', 'SaleController@delete')->name('delete_sales');;
            Route::post('/update/status', 'SaleController@updateStatus')->name('updateStatus');;
        });

        Route::group(['prefix' => 'clients'], function () {
            Route::get('/', 'ClientController@index')->name('clients');
            Route::get('/search', 'ClientController@index')->name('search_clients');
            Route::post('/', 'ClientController@createOrUpdate')->name('make_client');
            Route::delete('/', 'ClientController@delete')->name('delete_clients');;
        });

        Route::group(['prefix' => 'company'], function () {
            Route::post('/update', 'HomeController@updateCompany')->name('updateCompany');;
        });



        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductController@index')->name('products');
            Route::get('/search', 'ProductController@index')->name('search_products');
            Route::post('/', 'ProductController@createOrUpdate')->name('make_product');
            Route::delete('/', 'ProductController@delete')->name('delete_products');
            Route::delete('/photo', 'ProductController@deletePhoto')->name('delete_product_photo');
            Route::post('/photo', 'ProductController@getPhotos')->name('get_product_photos');

            Route::get('/sizes', 'ProductController@getSizes')->name('get_product_sizes');

        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('users');
            Route::get('/search', 'UserController@index')->name('search_users');
            Route::post('/', 'UserController@create');
            Route::put('/', 'UserController@update');
            Route::delete('/', 'UserController@delete');
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('categories');
            Route::get('/search', 'CategoryController@index')->name('search_categories');
            Route::post('/', 'CategoryController@createOrUpdate')->name('add_categories');
            Route::delete('/', 'CategoryController@delete')->name('delete_categories');;
        });

        Route::group(['prefix' => 'status'], function () {
            Route::get('/', 'StatusController@index')->name('status');
            Route::get('/search', 'StatusController@index')->name('search_status');
            Route::post('/', 'StatusController@createOrUpdate')->name('add_status');
            Route::delete('/', 'StatusController@delete')->name('delete_status');;
        });

        Route::group(['prefix' => 'sizes'], function () {
            Route::get('/', 'SizeController@index')->name('sizes');
            Route::get('/search', 'SizeController@index')->name('search_sizes');
            Route::post('/', 'SizeController@createOrUpdate')->name('add_sizes');
            Route::delete('/', 'SizeController@delete')->name('delete_sizes');;
        });
        
    });
});
