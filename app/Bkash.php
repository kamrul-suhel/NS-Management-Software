<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bkash extends Model
{
    //
    protected $fillable = [
        'amount',
        'phone_number',
        'status'
    ];

    const STATUS_PROCESSING = 0;
    const STATUS_COMPLETE = 1;

}
