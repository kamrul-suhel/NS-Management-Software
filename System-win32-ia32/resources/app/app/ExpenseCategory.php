<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'description'
    ];

    //
    public function expenses(){
        return $this->hasMany('App\Expense', 'expense_categories_id');
    }
}
