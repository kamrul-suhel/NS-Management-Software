<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissingProduct extends Model
{
    //
    protected $table = 'missing_product';

    protected $fillable = [
        'note'
    ];
}
