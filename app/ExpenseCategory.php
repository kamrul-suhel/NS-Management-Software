<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'title',
        'description'
    ];

    //
    public function expenses(){
        return $this->hasMany('App\Expense', 'expense_categories_id');
    }
}
