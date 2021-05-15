<?php

use Illuminate\Support\Facades\Route;

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

Route::get("/start", function(){
    return View("description");
})->name("start");

Route::get("/query1", function()
{
    $genres = \App\Http\Controllers\ConnectionController::readGenres();
    return View("query1", ['generi' => $genres]);
})->name("query1");
Route::post("/exec-query1", '\App\Http\Controllers\ConnectionController@query1')->name("exec-query1");

Route::get("/query2", function(){
    $genres = \App\Http\Controllers\ConnectionController::readGenres();
    return View("query2", ['generi' => $genres]);
})->name("query2");
Route::post("/exec-query2", '\App\Http\Controllers\ConnectionController@query2')->name("exec-query2");

Route::get("/query3", function(){
    $playlists = \App\Http\Controllers\ConnectionController::readPlaylists();
    return View("query3", ['playlists' => $playlists]);
})->name("query3");
Route::post("/exec-query3", '\App\Http\Controllers\ConnectionController@query3')->name("exec-query3");

Route::get("/query4", function(){
    return View('query4');
})->name("query4");
Route::post("/exec-query4", '\App\Http\Controllers\ConnectionController@query4')->name("exec-query4");

Route::get("/query5", function(){
    return View('query5');
})->name("query5");
Route::post("/exec-query5", '\App\Http\Controllers\ConnectionController@query5')->name("exec-query5");

Route::get("/query6", function(){
    $playlists = \App\Http\Controllers\ConnectionController::readPlaylists();
    return View("query6", ['playlists' => $playlists]);
})->name("query6");
Route::post("/exec-query6", '\App\Http\Controllers\ConnectionController@query6')->name("exec-query6");

//Route::post("/start", "\\App\\Http\\Controllers\\ConnectionController@create")->name("start");
