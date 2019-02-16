<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //

     protected $fillable = [
		'company_name',
		'company_address',
		'company_email',
		'company_phone',
		'company_mobile',
		'company_fax',
        'company_shop_number',
         'company_website',
         'company_logo'
    ];
}
