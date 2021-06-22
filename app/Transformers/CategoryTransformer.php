<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform($categories)
    {
        $objs = [];
        foreach($categories as &$category){
            $newObject = [];
            $newObject['id'] = $category->id;
            $newObject['name'] = $category->name;
            array_push($objs,$newObject);
        }

        return $objs;
    }
}
