<?php

namespace App\Repositories;

use App\Models\CompanyTokenActive;
use App\Repositories\Contracts\CompanyTokenRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class CompanyTokenRepository extends BaseRepository implements CompanyTokenRepositoryInterface
{
    public function model()
    {
        return CompanyTokenActive::class;
    }
}
