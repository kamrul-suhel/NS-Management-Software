<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'mobile',
        'fax',
        'serial',
        'website',
        'logo'
    ];
}
