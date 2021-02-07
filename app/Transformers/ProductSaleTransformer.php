<?php

namespace App\Transformers;

use App\Library\Format;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection;

class ProductSaleTransformer extends TransformerAbstract
{
    public function transform($products)
    {
        $objs = [];
        foreach ($products as &$product) {
            $newObject = [];
            $newObject['name'] = $product->name;
            $newObject['valor_moeda'] = Format::money(str_replace(",", '.', $product->pivot->sale_value));
            $newObject['quantity'] = $product->pivot->quantity;
            array_push($objs, $newObject);
        }
        return Collection::make($objs);
    }
}
