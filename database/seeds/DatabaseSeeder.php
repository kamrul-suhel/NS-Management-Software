<?php

use App\Company;
use App\CompanyTransaction;
use App\Customer;
use App\Expense;
use App\ExpenseCategory;
use App\Hotel;
use App\Rent;
use App\Store;
use App\User;
use App\Room;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    	$this->call(
//    		[
//    			DatabaseSeeder::class,
//    			DatabaseSeederClient::class,
//                DatabaseSeederWork::class
//			]
//		);

    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Hotel::truncate();
        Room::truncate();
        Rent::truncate();
        Expense::truncate();
        ExpenseCategory::truncate();

        User::flushEventListeners();
        Room::flushEventListeners();
        Expense::flushEventListeners();
        Hotel::flushEventListeners();
        Rent::flushEventListeners();
        ExpenseCategory::flushEventListeners();

        $usersQuantity = 5;
        $roomQuantity = 40;

        factory(User::class, $usersQuantity)->create();

        factory(Hotel::class, 3)->create();

        factory(Room::class, $roomQuantity)->create();

        factory(Rent::class, 500)->create();
    }
}
