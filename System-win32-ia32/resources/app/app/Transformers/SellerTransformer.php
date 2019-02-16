<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier'    => (int) $seller->id,
            'email'    => (string) $seller->email,
            'name'    => (string) $seller->name,
            'isverify'    => (int) $seller->verified,
            'creationdate'    => (string) $seller->created_at,
            'lastchange'    => (string) $seller->updated_at,
            'deleteddate'   => isset($seller->deleted_at) ? (string) $seller->deleted_al : null,
            'links' =>[
                [
                    'rel'   => 'seller',
                    'href'  => route('seller.show',$seller->id)
                ],
                [
                    'rel'   => 'seller.buyer',
                    'href'  => route('sellers.buyers.index',$seller->id)
                ],
                [
                    'rel'   => 'seller.category',
                    'href'  => route('sellers.categories.index',$seller->id)
                ],
                [
                    'rel'   => 'seller.products',
                    'href'  => route('sellers.products.index',$seller->id)
                ],
                [
                    'rel'   => 'seller.transation',
                    'href'  => route('sellers.transactions.index',$seller->id)
                ],
            ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identifier'    => 'id',
            'email'    => 'email',
            'name'    => 'name',
            'isverify'    => 'verified',
            'creationdate'    => 'created_at',
            'lastchange'    => 'updated_at',
            'deleteddate'   => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identifier',
            'email' => 'email',
            'name' => 'name',
            'verified' => 'isverify',
            'created_at' => 'creationdate',
            'updated_at' => 'lastchange',
            'deleted_at' => 'deleteddate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
