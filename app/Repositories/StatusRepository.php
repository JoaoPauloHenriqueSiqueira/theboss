<?php

namespace App\Repositories;

use App\Models\Statuses;
use App\Repositories\Contracts\StatusRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class StatusRepository extends BaseRepository implements StatusRepositoryInterface
{
    public function model()
    {
        return Statuses::class;
    }
}
