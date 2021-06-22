<?php

namespace App\Repositories;

use App\Models\Providers;
use App\Repositories\Contracts\ProviderRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    public function model()
    {
        return Providers::class;
    }
}
