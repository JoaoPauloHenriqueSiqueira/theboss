<?php

namespace App\Repositories;

use App\Models\Clients;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    public function model()
    {
        return Clients::class;
    }
}
