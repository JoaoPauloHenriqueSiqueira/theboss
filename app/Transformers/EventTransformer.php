<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    public function transform($events)
    {
        foreach ($events as $event) {
            $event->title = $event->client->name . ' - ' . $event->client->cell_phone;
            $event->start = $event->getDateSaleNormalAttribute();
            $products =  $event->products;
            $duration = 0;

            foreach ($products as $product) {
                if ($product->control_time) {
                    $duration += $product->duration;
                }
            }

            $event->end = $event->getDateSaleNormalFinishAttribute($duration);
        }

        return $events->get();
    }
}
