<?php

namespace App\Repositories;

use App\Models\Products;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function model()
    {
        return Products::class;
    }
}
