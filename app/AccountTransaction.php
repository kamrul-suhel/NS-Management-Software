<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransaction extends Model
{
    use SoftDeletes;

    CONST PAYMENT_TYPE_COMPANY = 1;
    CONST PAYMENT_TYPE_INVEST = 2;
    CONST PAYMENT_TYPE_CASH_IN = 3;
    CONST PAYMENT_TYPE_WITHDRAW = 4;
    CONST PAYMENT_TYPE_TRANSACTION = 5;

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'payment_type',
        'amount',
        'reference'
    ];

    /**
     * @param $paymentType
     * @return string
     */
    public function getPaymentTypeAttribute($paymentType){
        switch($paymentType){
            case self::PAYMENT_TYPE_COMPANY:
                return 'Company';
                break;

            case self::PAYMENT_TYPE_INVEST:
                return 'Invest';
                break;

            case self::PAYMENT_TYPE_CASH_IN:
                return 'Cash In';
                break;

            case self::PAYMENT_TYPE_WITHDRAW:
                return 'Withdraw';
                break;

            case self::PAYMENT_TYPE_TRANSACTION:
                return 'Transaction';
                break;
        }
    }

    public function account(){
        return $this->belongsTo('App\Account', 'account_id','id');
    }

    /**
     * @param $paymentType
     */
    public function setPaymentTypeAttribute($paymentType){
        switch($paymentType){
            case 'Company':
                $this->attributes['payment_type'] =  1;
                break;

            case 'Invest':
                $this->attributes['payment_type'] =  2;
                break;

            case 'Cash In':
                $this->attributes['payment_type'] =  3;
                break;

            case 'Withdraw':
                $this->attributes['payment_type'] =  4;
                break;

            case 'Transaction':
                $this->attributes['payment_type'] =  5;
                break;
        }
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatus($status){
        switch($status){
            case 1:
                return 'Yes';
                break;

            case 0:
                return 'No';
        }
    }

    /**
     * @param $status
     */
    public function setStatusAttribute($status){
        switch($status){
            case 'Yes':
                $this->attributes['status'] = 1;
                break;

            case 'No':
                $this->attributes['status'] = 0;
                break;

            default:
                $this->attributes['status'] = 1;
        }
    }
}
