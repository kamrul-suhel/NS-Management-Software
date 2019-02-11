<?php

use App\Company;
use App\Customer;
use App\Hotel;
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
    $hotelId = Hotel::all()->random()->id;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'type' => $faker->randomElement(['manager', 'staff']),
        'hotel_id' => $hotelId,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'verification_token' => $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin' => $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER])
    ];
});

$factory->define(Hotel::class, function(Faker $faker){
    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'fax' => $faker->phoneNumber,
        'website' => $faker->domainName,
        'logo' => $faker->imageUrl(640, 480)
    ];
});


$factory->define(Room::class, function (Faker $faker) {
    $hotel_id = Hotel::all()->random();

    return [
        'hotel_id' => $hotel_id->id,
        'title' => $faker->title,
        'description' => $faker->paragraph(1),
        'status' => $faker->randomElement([Room::UNAVAILABLE_PRODUCT, Room::ABAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg']),
        'price' => $faker->numberBetween(250, 500),
        'additional_price' => $faker->numberBetween(50, 100)
    ];
});

$factory->define(Rent::class, function (Faker $faker) {

    $user = User::all()->random();
    $room = Room::all()->random();
    $hotel = Hotel::findOrFail($room->hotel_id);

    return[
        'hotel_id'          => $hotel->id,
		'staff_id' 		    => $user->id,
		'room_id' 		    => $room->id,
        'client_name'   	=> $faker->name($faker->randomElement(['male', 'female'])),
        'father_name'       => $faker->name('male'),
        'client_address' 	=> $faker->address,
        'client_phone' 		=> $faker->phoneNumber,
        'discount_amount' 	=> $faker->numberBetween(50, 100),
        'total' 			=> $faker->numberBetween(500, 2500),
        'check_in'    	    => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'),
        'check_out'    	    => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'),
        'created_at'    	=> $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'),
        'updated_at'    	=> $faker->dateTimeBetween($startDate = '-6 day', $endDate = 'now')
    ];
});


/**
 * Expense section
 */
$factory->define(App\Expense::class, function (Faker $faker) {
    $store = Store::all()->random();

    return [
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