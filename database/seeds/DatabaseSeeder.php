<?php

use App\Company;
use App\CompanyTransaction;
use App\Customer;
use App\Expense;
use App\ExpenseCategory;
use App\Store;
use App\User;
use App\Product;
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
        $this->call(
            [
//                DatabaseSeeder::class,
                DatabaseSeederClient::class,
//                DatabaseSeederWork::class
            ]
        );
    }

    private function generateProductSerialArray(){
        $faker = new Faker();
        $digits = $faker->numberBetween(3, 5);
        $data = [];
        for($i = 0; $i<=$digits; $i++) {
            $data['product_serial'] = $faker->unique()->randomDigit;
            $data['is_sold'] = $faker->numberBetween(0, 1);
        }
        return $data;
    }
}
