<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    public function transform($client)
    {
        $newObject = [];
        $newObject['id'] = $client->id;
        $newObject['name'] = $client->name;
        $newObject['cpf_cnpj'] = $client->cpf_cnpj;
        $newObject['phone'] = $client->phone;
        $newObject['cell_phone'] = $client->cell_phone;
        $newObject['address'] = $client->address;
        $newObject['email'] = $client->email;
        $newObject['notifiable'] = $client->notifiable;
        $newObject['metadata'] = $client->metadata;

        return $newObject;
    }
}
