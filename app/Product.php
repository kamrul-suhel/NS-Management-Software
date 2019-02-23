<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public $transformer = ProductTransformer::class;
    protected $dates = [
        'deleted_at'
    ];

	const UNAVAILABLE_PRODUCT = 'unavailable';
	const ABAILABLE_PRODUCT = 'available';

	const PRODUCTTYPEFEET = 'feet';
	const PRODUCTTYPEPIC = 'pic';

	const NOWARRANTY = 'No warranty';


    protected $fillable = [
    	'name',
    	'description',
    	'status',
    	'quantity',
        'store_id',
        'feet',
        'quantity_per_feet',
    	'image',
    	'seller_id',
        'quantity_type',
        'sale_price',
        'purchase_price',
        'barcode',
        'is_barcode'
    ];

    protected $hidden = [
        'pivot',
        'deleted_at'
    ];

    public function isAbaliable(){
    	return $this->status == Product::ABAILABLE_PRODUCT;
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function companies(){
        return $this->belongsToMany(Company::class)
            ->withPivot('product_quantity', 'product_feet')
            ->withTimestamps();
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transitions(){
        return $this->belongsToMany(Transaction::class)
            ->withPivot(['sale_quantity'])
            ->withTimestamps();
    }


    // getter and setter
    public function setQuantityTypeAttribute($value){
        $this->attributes['quantity_type'] = strtolower($value);
    }

    public static function getQuantityType(){
        return [
            self::PRODUCTTYPEFEET,
            self::PRODUCTTYPEPIC
        ];
    }

    public function serials(){
        return $this->hasMany(ProductSerial::class);
    }

    public static function generateBarcode($length = 11) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomString = '';

        while(true){
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $product = self::where('barcode', $randomString)
                ->first();

            if(!$product){
                break;
            }
        }

        return $randomString;

    }

}
