<?php

namespace App\Repositories;

use App\Models\Flows;
use App\Repositories\Contracts\FlowRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class FlowRepository extends BaseRepository implements FlowRepositoryInterface
{
    public function model()
    {
        return Flows::class;
    }
}
