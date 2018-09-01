<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    //
    protected $fillable =[
        'name',
        'address',
        'description',
        'reference_number',
        'phone',
        'mobile',
        'fax',
        'email',
        'websiteurl',
        'city',
        'status'
    ];

    //relationship
    public function transactions(){
        return $this->hasMany(CompanyTransaction::class);
    }

    public function getStatusAttribute($value){
        return $value == 1 ? 'Active' : 'Inactive';
    }

    public function setStatusAttribute($value){
        $this->attributes['status'] = $value == 'Active'? 1 : 0;
    }

    public function products(){
        return $this->belongsToMany(Product::class)
            ->withPivot('product_quantity');
    }


}
