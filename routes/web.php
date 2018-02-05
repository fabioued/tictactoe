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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/new-game-with-{username}',[
    'uses'  =>'HomeController@newGame',
    'as'    => 'newGame'
]);

Route::get('/board/{id}',[
    'uses'  =>'GameController@board',
    'as'    => 'board'
]);

Route::get('/board/{game_id}/response=invite-ok',[
    'uses'  =>'HomeController@invite',
    'as'    => 'invite'
]);

Route::get('/board/{game_id}/response=invite-refused',[
    'uses'  =>'HomeController@invite',
    'as'    => 'invite'
]);

Route::post('/play/{id}',[
    'uses'  =>'GameController@play',
    'as'    => 'play'
]);

Route::post('/game-over/{id}',[
    'uses'  =>'GameController@gameOver',
    'as'    => 'play'
]);
