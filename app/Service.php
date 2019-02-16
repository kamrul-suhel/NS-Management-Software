<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = [
        'brand',
        'problem',
        'description',
        'service_charge',
        'status'
    ];
}
