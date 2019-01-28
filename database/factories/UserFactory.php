<?php

use App\Company;
use App\Customer;
use App\Rent;
use App\Store;
use App\User;
use App\Room;
use App\Category;
use App\Transaction;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'type' => $faker->rendomElement(['manager', 'staff']),
//        'email' => 'nslaptop@bd.co.uk',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'verification_token' => $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin' => $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER])
    ];
});


$factory->define(Room::class, function (Faker $faker) {
    /*
     * *********************************
     * product serial
     * *********************************
     */
    $store_id = Store::all()->random();

    return [
        'store_id' => $store_id->id,
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'feet' => $faker->numberBetween(10, 20),
        'sale_price' => $sale_price = $faker->numberBetween(150, 200),
        'purchase_price' => $sale_price - $faker->numberBetween(10, $sale_price),
        'status' => $faker->randomElement([Room::UNAVAILABLE_PRODUCT, Room::ABAILABLE_PRODUCT]),
        'quantity_type' => $faker->randomElement([Room::PRODUCTTYPEFEET, Room::PRODUCTTYPEPIC]),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg']),
        'barcode' => $faker->ean13,
        'seller_id' => User::all()->random()->id,
    ];
});

$factory->define(Rent::class, function (Faker $faker) {

    $customer = Customer::all()->random();
    $seller = User::all()->random();
    $store = Store::all()->random();


    $unique_id='';
    while($is_exists = true){
        $unique_id = generateRandomString(11);
        $unique_id_exists = Transaction::where('invoice_number', '=', $unique_id)->first();
        if($unique_id_exists){
            continue;
        }else{
            break;
        }
    }

    return[
        'store_id'          => $store->id,
        'customer_id' 		=> $customer->id,
		'seller_id' 		=> $seller->id,
        'payment_status' 	=> $faker->randomElement([Transaction::TRANSACTION_STATUS_DUE, Transaction::TRANSICTION_STATUS_OK]),
        'payment_due'   	=> $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 4000),
        'paid'         		=> $faker->randomFloat($nbMaxDecimals =2, $min = 0, $max = 2000),
        'discount_amount' 	=> $faker->numberBetween(20, 50),
        'invoice_number' 	=> $unique_id,
        'type' 				=> $faker->randomElement(['paid', 'due-paid']),
        'total' 			=> $faker->numberBetween(3000, 4000),
        'created_at'    	=> $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'),
//        'created_at'    	=> $faker->dateTimeBetween($startDate = '-5 month', $endDate = 'now'),
        'updated_at'    	=> $faker->dateTimeBetween($startDate = '-6 day', $endDate = 'now')
    ];
});

$factory->define(Store::class, function(Faker $faker){
    return [
        'name'      => $faker->company,
        'address'   => $faker->address,
        'phone'     => $faker->phoneNumber,
        'mobile'    => $faker->phoneNumber,
        'email'     => $faker->email,
        'fax'       => $faker->phoneNumber,
        'serial'=> $faker->bankAccountNumber,
        'website'=> $faker->url,
        'logo'=> 'logo',
    ];
});

/**
 * Expense section
 */
$factory->define(App\Expense::class, function (Faker $faker) {
    $store = Store::all()->random();
    return [
        //
        'store_id'  => $store->id,
        'title' => $faker->title,
        'expense_categories_id' => $faker->numberBetween(1, 10),
        'description'   => $faker->paragraph(1),
        'payment_type'  => $faker->randomElement(['check', 'cheque']),
        'amount'        => $faker->randomFloat($nbMaxDecimals =2, $min = 0, $max = 2000),
        'created_at'    => $faker->dateTimeBetween($startDate = '-12 month', $endDate = 'now'),
        'updated_at'    => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now')
    ];
});

$factory->define(App\ExpenseCategory::class, function (Faker $faker) {
    $store = Store::all()->random();
    return [
        //
        'store_id' => $store->id,
        'title' => $faker->title,
        'description'   => $faker->paragraph(1),
    ];
});



/**
 * Company
 */
$factory->define(App\Company::class, function (Faker $faker) {
    $store = Store::all()->random();
    return [
        //
        'store_id' => $store->id,
        'name' => $faker->company,
        'address' => $faker->address,
        'description' => $faker->paragraph(1),
        'reference_number'  => generateRandomString(),
        'phone'        => $faker->phoneNumber,
        'mobile'        => $faker->phoneNumber,
        'email'        => $faker->email,
        'city'          => $faker->city,
        'status'        => $faker->numberBetween(1,2),
        'fax'        => $faker->phoneNumber,
        'websiteurl'        => $faker->url,
        'created_at'    => $faker->dateTimeBetween($startDate = '-12 month', $endDate = 'now'),
        'updated_at'    => $faker->dateTimeBetween($startDate = '-5 month', $endDate = 'now')
    ];
});


$factory->define(\App\CompanyTransaction::class, function (Faker $faker) {
    $store = Store::all()->random();
    return [
        //
        'store_id'  => $store->id,
        'company_id' => Company::all()->random()->id,
        'payment_type' => $faker->randomElement(['cash', 'cheque','product', 'other']),
        'reference' => generateRandomString(),
        'remarks'  => $faker->randomElement(['remarks', 'unremarks']),
        'debit'        => $faker->randomFloat(2,30000,  90000),
        'credit'        => $faker->randomFloat(2, 20000, 25000),
        'balance'        => $faker->randomFloat(2, 50000, 10000),
        'manuel_date'        => $faker->dateTimeBetween($startDate = '-12 month', $endDate = 'now'),
        'created_at'    => $created_at = $faker->dateTimeBetween($startDate = '-1 day', $endDate = 'now'),
        'updated_at'    => $faker->dateTimeBetween($startDate = $created_at, 'now + 1 hour')
//        'updated_at'    => $faker->dateTimeBetween($startDate = '-5 month', $endDate = 'now')
    ];
});



function generateRandomString($length = 11) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;

}
