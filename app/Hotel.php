<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    //

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'logo'
    ];
}
