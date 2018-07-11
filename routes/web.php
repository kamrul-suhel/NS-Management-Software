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


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home/my-tokens', 'HomeController@getTokens')->name('personal-tokens');
Route::get('/home/my-clients', 'HomeController@getClients')->name('personal-clients');
Route::get('/home/authorize-clients', 'HomeController@getAuthorizeClients')->name('authorize-clients');

Route::get('/',function(){
    return view('welcome');
})->middleware('guest');

Route::get('/products', function(){
    return view('welcome');
});

Route::get('/categories', function(){
    return view('welcome');
});

Route::get('/transaction', function(){
	return view('welcome');
});

Route::resource('customers', 'Customer\CustomerController')->only(['store', 'index', 'update']);
Route::resource('customers.transactions', 'Customer\CustomerTransitionController')->only(['index']);

Route::resource('settings', 'SettingController')->only(['index', 'update']);

Route::get('transaction/{id}/print', 'Transaction\TransactionController@showPrint');
Route::get('transaction/{id}/edit', 'Transaction\TransactionController@edit');
Route::get('transaction/create', 'Transaction\TransactionController@create');
Route::post('transaction/{id}/edit', 'Transaction\TransactionController@edit');
