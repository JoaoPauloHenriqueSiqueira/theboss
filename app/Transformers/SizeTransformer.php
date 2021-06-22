<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection;

class SizeTransformer extends TransformerAbstract
{
    public function transform($sizes)
    {
        $objs = [];
        foreach ($sizes as $size) {
            $newObject = [];
            $newObject['id'] = $size->id;
            $newObject['name'] = $size->name;
            array_push($objs, $newObject);
        }

        return Collection::make($objs);
    }
}
