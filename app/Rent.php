<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rent extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    const TRANSICTION_STATUS_OK = 1;
    const TRANSACTION_STATUS_DUE = 2;


    protected $fillable = [
        'client_name',
        'father_name',
        'client_address',
        'client_phone',
        'discount_amount',
        'total',
        'check_in',
        'check_out'
    ];

    protected $hidden =[
        'deleted_at','pivot'
    ];

     public function getCreatedAtAttribute($value){
        $dt = date("F j, Y, g:i a", strtotime($value));
        return $dt;
     }
}
