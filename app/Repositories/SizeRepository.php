<?php

namespace App\Repositories;

use App\Models\Sizes;
use App\Repositories\Contracts\ProviderRepositoryInterface;
use App\Repositories\Contracts\SizeRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class SizeRepository extends BaseRepository implements SizeRepositoryInterface
{
    public function model()
    {
        return Sizes::class;
    }
}
