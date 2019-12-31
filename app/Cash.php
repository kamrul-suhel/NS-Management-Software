<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    /**
     * CASH TYPE
     */
    const CASHTYPE_ADD = 1;
    const CASHTYPE_MINUS = 2;

    /**
     * @var string
     */
    protected $table = 'cash_manipulation';

    /**
     * @var array
     */
    protected $fillable = [
      'notes'
    ];
}
