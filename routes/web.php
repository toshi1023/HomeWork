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
    Route::resource('users', 'UserController');

    // CSVコントローラ(csvというURLをおいているが、あくまでルートの重複を避けるためにあり、csvというページに遷移するわけではない)
    Route::get('csv', 'CsvController@export')->name('csv.export');
    Route::post('csv', 'CsvController@import')->name('csv.import');
});

Auth::routes();
