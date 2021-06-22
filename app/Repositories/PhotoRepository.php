<?php

namespace App\Repositories;

use App\Models\Photos;
use App\Repositories\Contracts\PhotoRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class PhotoRepository extends BaseRepository implements PhotoRepositoryInterface
{
    public function model()
    {
        return Photos::class;
    }
}
