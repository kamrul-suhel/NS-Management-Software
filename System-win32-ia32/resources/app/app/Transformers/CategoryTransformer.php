<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier'    => (int) $category->id,
            'title'    => (string) $category->name,
            'detail'    => (string) $category->description,
            'creationdate'    => (string) $category->created_at,
            'lastchange'    => (string) $category->updated_at,
            'deleteddate'   => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' =>[
                [
                    'rel'   => 'self',
                    'href'  => route('categories.show', $category->id)
                ],
                [
                    'rel'   => 'category.buyer',
                    'href'  => route('categories.buyers.index',$category->id)
                ],
                [
                    'rel'   => 'category.product',
                    'href'  => route('categories.products.index',$category->id)
                ],
                [
                    'rel'   => 'category.seller',
                    'href'  => route('categories.sellers.index',$category->id)
                ],
                [
                    'rel'   => 'category.transaction',
                    'href'  => route('categories.transactions.index',$category->id)
                ],
            ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identifier'    => 'id',
            'title'    => 'name',
            'detail'    => 'description',
            'creationdate'    => 'created_at',
            'lastchange'    => 'updated_at',
            'deleteddate'   => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identifier',
            'name' => 'title',
            'description' => 'detail',
            'created_at' => 'creationdate',
            'updated_at' => 'lastchange',
            'deleted_at' => 'deleteddate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
