<?php

use Illuminate\Http\Request;

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

/*
*  Buyer route
*/

Route::resource('buyers','Buyer\BuyerController',['only' => ['index', 'show']]);
Route::resource('buyers.transactions','Buyer\BuyerTransactionController',['only' => ['index']]);
Route::resource('buyers.sellers','Buyer\BuyerSellerController',['only' => ['index']]);
Route::resource('buyers.categories','Buyer\BuyerCategoryController',['only' => ['index']]);
Route::resource('buyers.products','Buyer\BuyerProductController',['only' => ['index']]);


/*
*  Category route
*/

Route::resource('categories','Category\CategoryController',['except' => ['create', 'edit']]);
Route::resource('categories.products','Category\CategoryProductController',['only' => ['index']]);
Route::resource('categories.sellers','Category\CategorySellerController',['only' => ['index']]);
Route::resource('categories.transactions','Category\CategoryTransactionController',['only' => ['index']]);
Route::resource('categories.buyers','Category\CategoryBuyerController',['only' => ['index']]);

/*
*  Product route
*/

Route::resource('products','Product\ProductController',['only' => ['index', 'show','destroy', 'store','update']]);
Route::resource('products.transactions','Product\ProductTransactionController',['only' => ['index']]);
Route::resource('products.buyers','Product\ProductBuyerController',['only' => ['index']]);
Route::resource('products.categories','Product\ProductCategoryController',['except' => ['edit','show','create','store']]);
Route::resource('customers.transactions','Product\ProductBuyerTransactionController',['only' => ['store']]);
Route::post('customer/{customer_id}/due/transactions', 'Customer\CustomerDueController@store')->name('customer.due.transaction');


/*
*  User route
*/

Route::resource('users','User\UserController',['except' => ['create', 'edit']]);
Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');


/*
 * Client auth
 */
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');



