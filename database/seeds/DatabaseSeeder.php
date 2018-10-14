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
    			DatabaseSeeder::class,
    			DatabaseSeederClient::class,
                DatabaseSeederWork::class
			]
		);


    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Customer::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        Expense::truncate();
        ExpenseCategory::truncate();
        Company::truncate();
        CompanyTransaction::truncate();
        Store::truncate();

        DB::table('category_product')->truncate();
        DB::table('product_transaction')->truncate();

        User::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();
        Transaction::flushEventListeners();
        Customer::flushEventListeners();
        Expense::flushEventListeners();
        ExpenseCategory::flushEventListeners();
        Company::flushEventListeners();
        CompanyTransaction::flushEventListeners();
        Store::flushEventListeners();

        $usersQuantity = 30;
        $customerQuantity = 10;
        $categoriesQuantity = 200;
        $productsQuantity = 200;
        $transactionQuantity = 200;

        factory(Store::class, 3)->create();

        $categoryRoot = Category::create([
            'name' => 'category 1',
            'store_id' => 1,
            'description' => 'Category description',
            'parent_id'   => null,
            'lft'          => 1,
            'rgt'           => 12
        ]);

        $categoryRoot->children()->create([
            'name' => 'category 2',
            'store_id' => 2,
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 2,
            'rgt'           => 3,
            'depth'         => 1
        ]);

        factory(User::class, $usersQuantity)->create();
        factory(Customer::class, $customerQuantity)->create();

        factory(Product::class, $productsQuantity)->create()->each(
        	function($product){
        		$categories = Category::all()->random(mt_rand(1, 5))->pluck('id');

        		$product->categories()->attach($categories);
        		$serials = $this->generateProductSerialArray();
        		$product->serials()->saveMany($serials);
        	}
        );

        factory(Company::class, 20)->create();
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
