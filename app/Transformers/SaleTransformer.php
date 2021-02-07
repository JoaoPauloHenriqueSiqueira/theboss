<?php

namespace App\Transformers;

use App\Library\Format;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection;

class SaleTransformer extends TransformerAbstract
{
    public function transform($sales)
    {
        $objs = [];
        foreach ($sales as $sale) {
            $newObject = [];
            $newObject['amount'] = Format::money(str_replace(",", '.', $sale->amount_paid));
            $newObject['date_sale'] = $sale->date_sale;
            $statuses = $sale->status;
            $statusesProduct = false;
            
            foreach($statuses as $status){
                $statusesProduct = $status->name;
            }

            $newObject['status'] = $statusesProduct;

            array_push($objs, $newObject);
        }

        return Collection::make($objs);
    }
}
