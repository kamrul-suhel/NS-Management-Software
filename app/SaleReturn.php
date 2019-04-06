<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    //
    protected $table = 'sales_return';

    protected $fillable = [
        'transaction_id',
        'total',
        'store_id',
        'seller_id',
        'note',
        'total'
    ];
}
