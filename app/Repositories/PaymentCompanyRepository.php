<?php

namespace App\Repositories;

use App\Models\PaymentCompany;
use App\Repositories\Contracts\PaymentCompanyRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class PaymentCompanyRepository extends BaseRepository implements PaymentCompanyRepositoryInterface
{
    public function model()
    {
        return PaymentCompany::class;
    }
}
