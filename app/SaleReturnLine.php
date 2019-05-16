<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturnLine extends Model
{
    //

    protected $table = 'sale_return_line';

    protected $fillable = [
        'sale_return_id',
        'product_id',
        'product_serial_id'
    ];
}
