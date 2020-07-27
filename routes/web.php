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


// 以下、ログインが必須なルート
Route::group(['middleware' => 'auth'], function() {

    // ホーム
    Route::get('/', 'HomeController@index')->name('home');

    // ユーザコントローラ
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/create', 'UserController@create')->name('users.create');
    Route::get('/users/{id}', 'UserController@show')->name('users.show');
    Route::put('/users/{id}', 'UserController@update')->name('users.update');
    Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/{id}/edit', 'UserController@edit')->name('users.edit');

    // カンパニーコントローラ
    Route::get('/companies', 'CompanyController@index')->name('companies.index');
    Route::post('/companies', 'CompanyController@store')->name('companies.store');
    Route::get('/companies/create', 'CompanyController@create')->name('companies.create');
    Route::get('/companies/{id}', 'CompanyController@show')->name('companies.show');
    Route::get('/companies/{id}', 'CompanyController@update')->name('companies.update');
    Route::get('/companies/{id}', 'CompanyController@destroy')->name('companies.destroy');
    Route::get('/companies/{id}/edit', 'CompanyController@edit')->name('companies.edit');

    // CSVコントローラ(csvというURLをおいているが、あくまでルートの重複を避けるためにあり、csvというページに遷移するわけではない)
    Route::get('csv', 'CsvController@export')->name('csv.export');
    Route::post('csv', 'CsvController@import')->name('csv.import');
});

Auth::routes();
