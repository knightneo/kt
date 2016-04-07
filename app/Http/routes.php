<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return ['OK'];
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::get('test', function () {
    $token = \Request::header('Authorization');
    #return \Request::header('Authorization') ? $token : 'no';
    return \Request::header();
});

Route::group(['middleware' => ['web']], function () {
    //
});

Route::post('signup', function () {
    return ['signup'];
});
Route::get('home/article_list/{page}', 'ArticleController@getArticleList');
Route::get('article/{article_id}', 'ArticleController@getArticleDetail');
Route::post('signin', 'AuthController@authenticate');
Route::group(['middleware' => ['jwt.auth',]], function () {
    Route::get('profile', 'AuthController@getAuthenticatedUser');
    Route::post('article', 'ArticleController@createArticle');
    Route::put('article/{article_id}', 'ArticleController@updateArticle');
    Route::get('article/{article_id}/publish', 'ArticleController@publish');
    Route::get('article/{article_id}/unpublish', 'ArticleController@unpublish');
    Route::get('article/{article_id}/delete', 'ArticleController@delete');
});
