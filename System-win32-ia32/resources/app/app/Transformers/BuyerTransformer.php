<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier'    => (int) $buyer->id,
            'email'    => (string) $buyer->email,
            'name'    => (string) $buyer->name,
            'isverify'    => (int) $buyer->verified,
            'creationdate'    => (string) $buyer->created_at,
            'lastchange'    => (string) $buyer->updated_at,
            'deleteddate'   => isset($buyer->deleted_at) ? (string) $buyer->deleted_al : null,
            'links' =>[
                [
                    'rel'  => 'buyers',
                    'href'  => route('buyers.index')
                ],
                [
                    'rel'   => 'buyers.show',
                    'href'  => route('buyers.show', $buyer->id)
                ],
                [
                    'rel'   => 'buyers.category.index',
                    'href'  => route('buyers.categories.index', $buyer->id)
                ],
                [
                    'rel'   => 'buyers.products.index',
                    'href'  => route('buyers.products.index', $buyer->id)
                ],
                [
                    'rel'   => 'buyers.sellers.index',
                    'href'  => route('buyers.sellers.index', $buyer->id)
                ],
                [
                    'rel'   => 'buyers.transactions.index',
                    'href'  => route('buyers.transactions.index', $buyer->id)
                ]
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
