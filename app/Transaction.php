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
    const PAYMENT_PENDING = 4;

    //
    protected $fillable = [
    	'quantity',
    	'customer_id',
    	'product_id',
		'seller_id',
        'store_id',
        'payment_status',
        'service_charge',
        'payment_due',
        'paid',
        'discount_amount',
        'special_discount',
        'total',
        'invoice_number',
        'type',
        'status',
        'approved_by'
    ];

    protected $hidden =[
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function customer(){
     	return $this->belongsTo(Customer::class);
     }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
     public function products(){
     	return $this->belongsToMany('App\Product')
            ->withPivot(['sale_quantity','sale_feet','discount_percentage'])
            ->withTimestamps();
     }

    /**
     * @return array
     */
     public static function getPaymentStatusType(){
        return [
            'paid' => self::PAYMENT_PAID,
            'half_paid' => self::PAYMENT_HALF_PAID,
            'due'   => self::PAYMENT_DUE
        ];
     }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
     public function serials(){
     	return $this->hasMany(ProductSerial::class);
	 }

    /**
     * @param $value
     * @return false|string
     */
     public function getCreatedAtAttribute($value){
        $dt = date("F j, Y, g:i a", strtotime($value));
        return $dt;
     }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function seller(){
     	return $this->belongsTo(User::class, 'seller_id', 'id');
	 }

    /**
     * @return string
     */
	 public function saleReturn(){
         return '';
     }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
     public function bkash(){
         return $this->hasOne('App\Bkash');
     }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
     public function accountTransaction(){
         return $this->hasOne('App\AccountTransaction', 'transaction_id', 'id');
     }

    /**
     * @param $status
     * @return int
     */
     public static function getPaymentStatus($status){
         switch($status){
             case 'paid':
                 return 1;

             case 'due-paid':
                 return 2;
             case 'half-paid':
                 return 3;
             case 'pending':
                 return 4;
         }
     }

}
