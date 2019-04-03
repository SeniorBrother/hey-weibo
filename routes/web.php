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


Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');
// 注册
Route::get('signup', 'UsersController@create')->name('signup');

//
Route::resource('users', 'UsersController');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');

// 登录展示
Route::get('login', 'SessionsController@create')->name('login'); // 显示登录
Route::post('login', 'SessionsController@store')->name('login'); // 创建会话
Route::delete('logout', 'SessionsController@destroy')->name('logout'); //销毁会话 退出登录

//用户个人信息编辑
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');

// 用户列表

// 邮件验证
Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');

// 重置密码
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');