<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSerial extends Model
{
    //
    use SoftDeletes;

    public $fillable = [
        'color',
        'barcode',
        'imei',
        'product_warranty',
        'is_sold',
        'company_id'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function company(){
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
