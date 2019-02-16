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

	const PRODUCTTYPEKG = 'kg';
	const PRODUCTTYPLITTER = 'litter';
	const PRODUCTTYPEITEM = 'item';
	const PRODUCTTYPEPIC = 'pic';


    protected $fillable = [
    	'name',
    	'description',
    	'status',
    	'quantity',
    	'image',
    	'seller_id',
        'quantity_type',
        'sale_price',
        'purchase_price',
        'barcode'
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
            ->withPivot('product_quantity')
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
            self::PRODUCTTYPEKG,
            self::PRODUCTTYPLITTER,
            self::PRODUCTTYPEPIC
        ];
    }

    public function serials(){
        return $this->hasMany(ProductSerial::class);
    }

}
