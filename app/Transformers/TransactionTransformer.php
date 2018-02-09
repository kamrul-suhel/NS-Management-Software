<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier'    => (int) $transaction->id,
            'quantity'    => (int) $transaction->quantity,
            'buyer'    => (int) $transaction->buyer_id,
            'product'    => (int) $transaction->product_id,
            'creationdate'    => (string) $transaction->created_at,
            'lastchange'    => (string) $transaction->updated_at,
            'deleteddate'   => isset($transaction->deleted_at) ? (string) $transaction->deleted_al : null,
            'links' =>[
                [
                    'rel'   => 'self',
                    'href'  => route('transactions.show',$transaction->id)
                ],
                [
                    'rel'   => 'transaction.category',
                    'href'  => route('transactions.categories.index', $transaction->id)
                ],
                [
                    'rel'   => 'transaction.seller',
                    'href'  => route('transactions.sellers.index', $transaction->id)
                ],
            ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identifier'    => 'id',
            'quantity'    => 'quantity',
            'buyer'    => 'buyer_id',
            'product'    => 'product_id',
            'creationdate'    => 'created_at',
            'lastchange'    => 'updated_at',
            'deleteddate'   => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identifier',
            'quantity' => 'quantity',
            'buyer_id' => 'buyer',
            'product_id' => 'product',
            'created_at' => 'creationdate',
            'updated_at' => 'lastchange',
            'deleted_at' => 'deleteddate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
