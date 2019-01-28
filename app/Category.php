<?php

namespace App;

use App\Room;
use App\Transformers\CategoryTransformer;
use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Node
{
    //
    use SoftDeletes;

    public $transformer = CategoryTransformer::class;

    protected $dates=['deleted_at'];

    protected $fillable = [
    	'name',
    	'description'
    ];

    protected $hidden =[
        'pivot',
        'deleted_at',
        'parent_id',
        'lft',
        'rgt',
        'depth'
    ];

    public function products(){
    	return $this->belongsToMany(Room::class);
    }
}
