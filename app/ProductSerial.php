<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSerial extends Model
{
    //
    use SoftDeletes;

    public $fillable = [
        'product_serial',
        'product_warranty',
        'is_sold',
        'company_id'
    ];

    public function product(){
        return $this->belongsTo(Room::class);
    }
}
