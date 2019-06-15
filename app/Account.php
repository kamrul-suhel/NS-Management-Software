<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    //

    protected $fillable = [
        'name',
        'account_number'
    ];

    public function transactions(){
        return $this->hasMany('App\AccountTransaction', 'account_id');
    }
}
