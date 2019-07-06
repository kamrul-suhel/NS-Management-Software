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
$this->get('login', 'LoginController@showLoginForm')->name('login');
$this->get('islogin', 'LoginController@isLogin')->name('islogin');
$this->post('login', 'LoginController@login');
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

/**
 * Product Route
 */
Route::get('/products', function(){return view('welcome');});
Route::get('/products/add', function(){return view('welcome');});
Route::get('/products/{id}/edit', function(){return view('welcome');});
Route::get('/products/{id}/delete', function(){return view('welcome');});


/**
 * Category route
 */
Route::get('/categories', function(){
    return view('welcome');
});


/**
 * Transaction route
 */
Route::get('/transaction', function(){
	return view('welcome');
});

/**
 * Sales return
 */
Route::get('/sale-return', function(){
    return view('welcome');
});

Route::get('/account/balance', function(){
    return view('welcome');
});

Route::resource('customers', 'Customer\CustomerController')->only(['store', 'index', 'update']);
Route::resource('customers.transactions', 'Customer\CustomerTransitionController')->only(['index']);

Route::resource('shops', 'ShopController')->only(['index', 'update', 'show','store']);

Route::get('transaction/{id}/print', 'Transaction\TransactionController@showPrint');
Route::get('transaction/{id}/edit', 'Transaction\TransactionController@edit');
Route::get('transaction/create', 'Transaction\TransactionController@create');
Route::post('transaction/{id}/edit', 'Transaction\TransactionController@edit');



/**
 * Expense frontend route
 */

Route::get('expense', 'Expense\ExpenseController@index')->name('expense.show');
Route::get('expensecategories', 'ExpenseCategory\ExpenseCategoryController@index')->name('expense.category.show');


/**
 * Company frontend route
 */

Route::get('company', 'Company\CompanyController@index')->name('company');
Route::get('company/transition/add', function(){
    return view('welcome');
});

Route::get('company/transition/edit', function(){
    return view('welcome');
});

Route::get('companytransaction', 'Company\CompanyController@index')->name('company');



/**
* Account frontend route
*/
Route::get('account/expense', function(){
    return view('welcome');
});

Route::get('account/transaction', function(){
    return view('welcome');
});


/*
 *
 * Customer Due route
 *
 */

Route::get('transaction/due/create', 'Customer\CustomerDueController@index');

Route::get('account/customer', function(){
   return view('welcome');
});

Route::get('banks', function(){
    return view('welcome');
});

Route::get('banks/{id}/accounts', function(){
    return view('welcome');
});

Route::get('banks/{bankId}/accounts/{accountId}/transactions', function(){
    return view('welcome');
});

/*
 * ************************************************
 * Database backup process
 * ************************************************
 */
Route::get('backup/create', 'DBBackupController@create');



/*
 *
 * User route
 *
 */

Route::get('/user', 'User\UserController@show');
