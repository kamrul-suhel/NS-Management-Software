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
class DatabaseSeederClient extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        $usersQuantity = 10;
        $customerQuantity = 10;
        $categoriesQuantity = 100;
        $productsQuantity = 20;
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

//        $categoryRoot->children()->create([
//            'name' => 'category 3',
//            'description' => 'Category description',
//            'parent_id'   => 1,
//            'lft'          => 4,
//            'rgt'           => 5,
//            'depth'         => 1
//        ]);
//
//        $categoryRoot->children()->create([
//            'name' => 'category 4',
//            'description' => 'Category description',
//            'parent_id'   => 1,
//            'lft'          => 6,
//            'rgt'           => 7,
//            'depth'         => 1
//        ]);
//
//        $categoryRoot->children()->create([
//            'name' => 'category 5',
//            'description' => 'Category description',
//            'parent_id'   => 1,
//            'lft'          => 8,
//            'rgt'           => 9,
//            'depth'         => 1
//        ]);
//
//        $categoryRoot->children()->create([
//            'name' => 'category 6',
//            'description' => 'Category description',
//            'name' => 'category 2',
//            'description' => 'Category description',
//            'parent_id'   => 1,
//            'lft'          => 10,
//            'rgt'           => 11,
//            'depth'         => 1
//        ]);

//        factory(Category::class, $categoriesQuantity)->create()->each(function($category){
//            $root = Category::root();
//            if($category->id == 1){
//                $category->makeRoot();
//                return;
//            }
//            $category->makeChildOf($root);
//        });



        factory(User::class, $usersQuantity)->create();
//        factory(Customer::class, $customerQuantity)->create();

//        factory(Product::class, $productsQuantity)->create()->each(
//        	function($product){
//        		$categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
//
//        		$product->categories()->attach($categories);
//                $serials = $this->generateProductSerialArray();
//                $product->serials()->createMany($serials);
//        	}
//        );

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

        factory(Store::class, 1)->create();


        /**
         * Expense seeder
         */

//        factory(ExpenseCategory::class, 10)->create();
//        factory(Expense::class, 200)->create();


        /**
         * Company seeder
         */

//        factory(Company::class, 20)->create()->each(function($company){
//            $products = Product::all()->random(mt_rand(1,5))->pluck('id');
//            $company->products()->attach($products,
//                [
//                   'product_quantity' => Faker::create()->numberBetween(1, 5)
//                ]);
//        });
//
//        factory(CompanyTransaction::class, 200)->create();
    }

    private function generateProductSerialArray(){
        $faker = new Faker();
        $digits = Faker::create()->numberBetween(2, 3);
        $allData = [];
        for($i = 0; $i<=$digits; $i++){
            $data = [];
            $data['product_serial'] = Faker::create()->randomNumber($nbDigits = NULL);
            $data['product_warranty'] = Faker::create()->randomElement(['3 Month','6 Month','1 Year', '1.5 Year']);
            $data['is_sold'] = Faker::create()->numberBetween(0, 1);
            $data['company_id'] = Faker::create()->numberBetween(1, 20);
            $allData[] = $data;
        }

        return $allData;

    }
}
