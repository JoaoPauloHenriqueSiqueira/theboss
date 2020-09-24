<?php

namespace App\Repositories;

use App\Models\Sales;
use App\Repositories\Contracts\SaleRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class SaleRepository extends BaseRepository implements SaleRepositoryInterface
{
    public function model()
    {
        return Sales::class;
    }
}
