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

Route::post('product/missing/add', 'Product\ProductMissingStoreController@store');
Route::get('product/missing/list', 'Product\ProductMissingListController@list');


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

/**
 * Customer Ledger route
 */
Route::post('customerledger/create', 'CustomerLedgerController@store');

/*
*  Product Serial route
*/
Route::resource('product_serials', 'Product\ProductSerialController', ['only' => ['destroy']]);

/*
*  Transition route
*/

Route::resource('transactions','Transaction\TransactionController',['only' => ['index', 'show', 'update']]);
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only' => ['index']]);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only' => ['index']]);
Route::get('transactions/search/search','Transaction\TransactionController@searchByInvoice');
Route::get('transactions/{id}/delete','Transaction\TransactionController@destroy');


/*
*  Sale Return route
*/
Route::post('sale-return', 'SaleReturnController@store');


/*
*  Seller route
*/

Route::resource('sellers','Seller\SellerController',['only' => ['index', 'show']]);
Route::resource('sellers.transactions','Seller\SellerTransactionController',['only' => ['index']]);
Route::resource('sellers.categories','Seller\SellerCategoryController',['only' => ['index']]);
Route::resource('sellers.buyers','Seller\SellerBuyerController',['only' => ['index']]);
Route::resource('sellers.products','Seller\SellerProductController',['except' => ['show','edit','create']]);


/*
*  Expense route
*/
Route::resource('expense', 'Expense\ExpenseController', ['except' => ['edit', 'create', 'show']]);

/*
*  Expense Categories route
*/
Route::resource('expensecategory', 'ExpenseCategory\ExpenseCategoryController', ['except' => ['edit', 'create']]);


/*
*  Company route
*/
Route::resource('company', 'Company\CompanyController', ['except' => ['edit', 'create']]);
Route::get('selectedcompany/{id}', 'Company\CompanyTransactionController@selectedCompany')->name('selected_company');
Route::get('productcompany', 'Company\CompanyController@productCompany')->name('product_company');
Route::resource('ctransaction', 'Company\CompanyTransactionController', ['except' => ['edit', 'create', 'show']]);
Route::get('companies/{id}/product', 'Company\CompanyProductController@list');
Route::get('companies/product/{id}', 'Company\CompanyProductController@single');
Route::post('companies/return', 'Company\CompanyReturnStoreController@store');
Route::get('companies/return/{companyId}', 'Company\CompanyReturnListController@list');

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



/*
 *
 * Customer route
 *
 */

Route::resource('customers', 'Customer\CustomerController');



/*
 * ************************************************
 * Accounting go here
 * ************************************************
 */
Route::post('accounting/transaction', 'Accounting\TransactionAccountingController@index')->name('transaction.accounting');


/*
 * ************************************************
 * Bank go here
 * ************************************************
 */
Route::resource('banks', 'Bank\BankController', ['only'=> ['index','store', 'update','destroy']]);


/*
 * ************************************************
 * Bank accounts go here
 * ************************************************
 */
Route::resource('banks.accounts', 'Bank\AccountController', ['only'=> ['index','store', 'update','destroy']]);
Route::get('bankaccounts', 'Bank\AccountController@getAllAccount');


/*
 * ************************************************
 * Account Transaction go here
 * ************************************************
 */
Route::resource('banks.accounts.accountTransactions', 'Bank\AccountTransactionController', ['only'=> ['index','store', 'update','destroy']]);


/*
 * ************************************************
 * Sale assistant route go here
 * ************************************************
 */
//Route::resource('SaleAssistance.saleAssistant', 'SaleAssistance\SaleAssistanceController', ['only'=> ['index','store', 'update','destroy']]);

Route::get('/sale-assistant/product', 'SaleAssistance\SaleAssistanceController@getScannedProduct');
Route::post('/sale-assistant/create', 'SaleAssistance\SaleAssistanceController@createSaleAssistant');
Route::get('/sale-assistant/products', 'SaleAssistance\SaleAssistanceController@getSaleAssistantProducts');
