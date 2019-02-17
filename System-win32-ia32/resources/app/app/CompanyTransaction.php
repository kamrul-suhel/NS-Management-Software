<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTransaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    //

    protected $fillable = [
      'company_id',
      'payment_type',
      'reference',
        'remarks',
        'debit',
        'credit',
        'balance',
        'manuel_date'
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }


}
