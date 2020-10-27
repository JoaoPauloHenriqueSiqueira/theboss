<?php

namespace App\Transformers;

use App\Models\Categories;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform($categories)
    {
        $objs = [];
        foreach($categories as &$category){
            $newObject = [];
            $newObject['name'] = $category->name;
            array_push($objs,$newObject);
        }
        $return = [];
        $return['data'] =  $objs;
        return $return;
    }
}
