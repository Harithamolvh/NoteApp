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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/registration', 'ReceiverController@newregistration')->name('apiregistration');

Route::post('/new/note', 'ReceiverController@newnote')->name('apinewnote')->middleware('user')->middleware('user');

Route::get('/all/note', 'ReceiverController@getallnote')->name('apigetallnote')->middleware('user')->middleware('user');
Route::get('/note/view', 'ReceiverController@noteview')->name('apinoteview')->middleware('user')->middleware('user');

Route::post('/delete/note', 'ReceiverController@deletenote')->name('apideletenote')->middleware('user');

Route::post('/edit/note', 'ReceiverController@editnote')->name('apieditnote')->middleware('user');


