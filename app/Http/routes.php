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
Route::group(['prefix' => 'test', 'middleware' => 'permission'], function () {
    Route::get('/', function() {});
});

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['middleware' => 'permission:read'], function() {
    Route::post('signup', 'AuthController@createUser');
    Route::post('signin', 'AuthController@authenticate');
    Route::post('check/email/available', 'AuthController@isEmailAvailable');
    Route::get('home/article_list/{page}', 'ArticleController@getArticleList');
    Route::get('article/{article_id}', 'ArticleController@getArticleDetail');
});

Route::group(['middleware' => ['jwt.auth', 'permission:read']], function () {
    Route::get('profile', 'AuthController@getAuthenticatedUser');
    Route::post('reset/password', 'AuthController@userResetPassword');
});

Route::group(['middleware' => ['jwt.auth', 'permission:write']], function () {
    Route::post('article', 'ArticleController@createArticle');
    Route::put('article/{article_id}', 'ArticleController@updateArticle');
    Route::get('article/{article_id}/delete', 'ArticleController@delete');
    Route::get('user/article/{page}', 'ArticleController@getUserArticleList');
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.auth']], function () {
    Route::group(['prefix' => 'role', 'middleware' => ['permission:role']], function () {
        Route::get('list', 'AdminController@getRoleList');
        Route::put('{user_id}', 'AdminController@setRole');
    });
    Route::group(['prefix' => 'permission', 'middleware' => ['permission:permission']], function () {
        Route::get('list', 'AdminController@getPermissionList');
        Route::put('{role_id}', 'AdminController@setPermission');
    });
    Route::group(['prefix' => 'user', 'middleware' => ['permission:user']], function () {
        Route::get('list/{page}', 'AdminController@getUserList');
    });
    Route::group(['prefix' => 'user', 'middleware' => ['permission:password']], function () {
        Route::post('reset/password', 'AdminController@adminResetPassword');
    });
});
