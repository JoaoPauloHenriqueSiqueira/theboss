<?php

namespace App\Transformers;

use App\Library\Format;
use App\Models\Categories;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform($products)
    {
        $objs = [];
        foreach ($products as &$product) {
            if ($product->control_quantity && $product->quantity <= 0) {
                continue;
            }
            
            $newObject = [];
            $newObject['id'] = $product->id;
            $newObject['name'] = $product->name;

            $newObject['valor_moeda'] = Format::money(str_replace(",", '.', $product->sale_value));
            $newObject['valor'] = str_replace(",", '.', $product->sale_value);
            $newObject['control_quantity'] = $product->control_quantity;


            $newObject['quantity'] = $product->quantity;


            $photosArray = [];
            foreach ($product->photos as $photo) {
                array_push($photosArray,  env('AWS_URL') . $photo->path);
            }

            $newObject['photos'] = $photosArray;
            array_push($objs, $newObject);
        }

        return $objs;
    }
}
