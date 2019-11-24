<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    //
    protected $table = 'customer_ledgers';

    protected $fillable = [
      'reference',
        'remark',
        'particular'
    ];
}
