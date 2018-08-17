<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

//    public $transformer = TransactionTransformer::class;
    protected $dates = ['deleted_at'];

    //Transaction product
    const TRANSICTION_STATUS_OK = 1;
    const TRANSACTION_STATUS_DUE = 2;

    const PAYMENT_PAID = 1;
    const PAYMENT_DUE = 2;
    const PAYMENT_HALF_PAID = 3;

    //
    protected $fillable = [
    	'quantity',
    	'customer_id',
    	'product_id',
        'payment_status',
        'service_charge',
        'payment_due',
        'paid',
        'discount_amount',
        'total',
        'invoice_number',
        'type'
    ];

    protected $hidden =[
        'deleted_at','pivot'
    ];

     public function customer(){
     	return $this->belongsTo(Customer::class);
     }

     public function products(){
     	return $this->belongsToMany(Product::class)
            ->withPivot(['sale_quantity'])
            ->withTimestamps();
     }

     public static function getPaymentStatusType(){
        return [
            'paid' => self::PAYMENT_PAID,
            'half_paid' => self::PAYMENT_HALF_PAID,
            'due'   => self::PAYMENT_DUE
        ];
     }

     public function serials(){
     	return $this->hasMany(ProductSerial::class);
	 }

     public function getCreatedAtAttribute($value){
        $dt = date("F j, Y, g:i a", strtotime($value));
        return $dt;
     }

}
