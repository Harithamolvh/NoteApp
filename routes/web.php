<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Web Routes
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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('user');

Route::post('/registration', 'UserController@registration')->name('registration');
Route::post('/new/note', 'UserController@newnote')->name('newnote')->middleware('user')->middleware('user');

Route::get('/note/{id}', function () {
    return view('note');
})->name('note')->middleware('user');

Route::post('/delete/note', 'UserController@deletenote')->name('deletenote')->middleware('user');

Route::get('/edit/note/{id}', function () {
    return view('editnote');
})->name('noteedit')->middleware('user');

Route::post('/edit/note/{id}', 'UserController@editnote')->name('editnote')->middleware('user');

