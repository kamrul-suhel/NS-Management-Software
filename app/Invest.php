<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invest extends Model
{
    //

    protected $fillable = [
        'title',
        'description',
        'total',
        'category_id'
    ];
}
