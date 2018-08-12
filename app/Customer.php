<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobile',
        'address',
        'active',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function transitions(){
        return $this->hasMany(Transaction::class);
    }

    public function duePayments(){
        return $this->hasMany(CustomerDue::class);
    }
}
