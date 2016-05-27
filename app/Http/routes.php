<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::auth();
//Route::get('/home', 'HomeController@index');

/*--------------------------------------*/
Route::get('', function () {
    return view('blog.index.index');
});

//логин
Route::get('login', 'Blog\LoginController@login');
Route::post('login', 'Blog\LoginController@loginUser');
//логаут из системы
Route::get('logout', 'Blog\LoginController@logout');

//Кантроллер заметок с авторизацией
Route::group(['middleware' => 'auth'], function()
{
    Route::get('notes/create', 'Blog\Notes\NotesController@createShow');    //Показ формы добавления
    Route::post('notes/create', 'Blog\Notes\NotesController@createAdd');    //Обработка формы добавления
});
//Кантроллер заметок без авторизации
Route::group([], function()
{
    Route::get('notes', 'Blog\Notes\NotesController@showAll');  //Показ всех заметок
    Route::get('notes/{id}', 'Blog\Notes\NotesController@showOne');  //Показ еденичной записи
});

Route::get('about', function () {
    return view('blog.about.about');
});
