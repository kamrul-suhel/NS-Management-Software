<?php

use App\Company;
use App\CompanyTransaction;
use App\Customer;
use App\Expense;
use App\ExpenseCategory;
use App\ProductSerial;
use App\Service;
use App\Setting;
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
//    	$this->call(
//    		[
//    			DatabaseSeeder::class,
//				DatabaseSeederDump::class
//			]
//		);


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
        Service::truncate();

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
        Service::flushEventListeners();

        $usersQuantity = 30;
        $customerQuantity = 10;
        $categoriesQuantity = 200;
        $productsQuantity = 200;
        $transactionQuantity = 200;

        $categoryRoot = Category::create([
            'name' => 'category 1',
            'description' => 'Category description',
            'parent_id'   => null,
            'lft'          => 1,
            'rgt'           => 12
        ]);


        $categoryRoot->children()->create([
            'name' => 'category 2',
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 2,
            'rgt'           => 3,
            'depth'         => 1
        ]);

        $categoryRoot->children()->create([
            'name' => 'category 3',
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 4,
            'rgt'           => 5,
            'depth'         => 1
        ]);

        $categoryRoot->children()->create([
            'name' => 'category 4',
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 6,
            'rgt'           => 7,
            'depth'         => 1
        ]);

        $categoryRoot->children()->create([
            'name' => 'category 5',
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 8,
            'rgt'           => 9,
            'depth'         => 1
        ]);

        $categoryRoot->children()->create([
            'name' => 'category 6',
            'description' => 'Category description',
            'name' => 'category 2',
            'description' => 'Category description',
            'parent_id'   => 1,
            'lft'          => 10,
            'rgt'           => 11,
            'depth'         => 1
        ]);

//        factory(Category::class, $categoriesQuantity)->create()->each(function($category){
//            $root = Category::root();
//            if($category->id == 1){
//                $category->makeRoot();
//                return;
//            }
//            $category->makeChildOf($root);
//        });



        factory(User::class, $usersQuantity)->create();
        factory(Customer::class, $customerQuantity)->create();

        //        /**
//         * Company seeder
//         */
//
        factory(Company::class, 20)->create();

        factory(Product::class, $productsQuantity)->create()->each(
        	function($product){
        		$categories = Category::all()->random(mt_rand(1, 5))->pluck('id');


        		$product->categories()->attach($categories);
        		$serials = $this->generateProductSerialArray();
        		$product->serials()->saveMany($serials);
        	}
        );

//
//        factory(Transaction::class, $transactionQuantity)->create()->each(
//            function($transaction){
//            $products = Product::all()->random(mt_rand(1,5))->pluck('id');
//            $transaction->products()->attach($products,
//                [
//                    'sale_quantity' => Faker::create()->numberBetween(1, 5),
//                    'created_at'    => Faker::create()->dateTimeBetween($startDate = '-12 month', $endDate = 'now'),
//                    'updated_at'    => Faker::create()->dateTimeBetween($startDate = '-5 month', $endDate = 'now')
//                ]);
//
//        });
//
        factory(Setting::class, 1)->create();

        factory(Service::class, 200)->create();
//
//
//        /**
//         * Expense seeder
//         */
//
//        factory(ExpenseCategory::class, 10)->create();
//        factory(Expense::class, 200)->create();
//
//
//
//        factory(CompanyTransaction::class, 200)->create();
    }
//
    private function generateProductSerialArray(){
        $faker = Faker::create();
        $digits = $faker->numberBetween(3, 5);

        $data = [];
        for($i = 0; $i<=$digits; $i++){
            $serial = new ProductSerial();
            $serial->product_serial = $faker->unique()->randomDigit;
            $serial->is_sold = $faker->numberBetween(0, 1);
            $data[$i] = $serial;
        }
//
        return $data;

    }
}
