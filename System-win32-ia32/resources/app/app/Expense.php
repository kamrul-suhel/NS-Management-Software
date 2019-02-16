<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    //
    use SoftDeletes;

    protected $dates=['deleted_at'];

    protected $fillable = [
        'title',
        'description',
        'amount',
        'payment_type',
        'expense_categories_id',
    ];

    public function category(){
        return $this->belongsTo('App\ExpenseCategory', 'expense_categories_id');
    }
}
