<?php

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
    return view('welcome');
});

// Login


// Account management
Route::group([
  'prefix' => 'account',
  'namespace' => 'Account'
], function () {

  Route::group(['prefix' => 'superadmin'], function () {
    Route::resource('', 'SuperAdminController', ['only' => ['index', 'store', 'update', 'destroy']]);
  });

  Route::group(['prefix' => 'user'], function () {
    Route::resource('', 'UserController', ['only' => ['update']]);
  });

});

// Blog management
Route::group(['prefix' => 'blog'], function () {
  Route::group(['prefix' => 'categories'], function () {
    Route::resource('', 'CategoryController', ['only' => ['store', 'update', 'destroy']]);
  });
  Route::group(['prefix' => 'article'], function () {
    Route::resource('', 'ArticleController', ['only' => ['show', 'store', 'update', 'destroy']]);
  });
});
