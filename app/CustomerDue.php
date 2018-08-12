<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDue extends Model
{
    //

    protected $fillable = [
        'paid',
        'due',
        'transaction_id'
    ];
}
