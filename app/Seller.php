<?php

namespace App;

use App\Transformers\SellerTransformer;
use App\User;
use App\Room;


class Seller extends User
{

    public $transformer = SellerTransformer::class;

	public function products(){
		return $this->hasMany(Room::class);
	}

}
