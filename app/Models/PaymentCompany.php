<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCompany extends Model
{
  
    protected $collection = 'payments_company';

    protected $fillable = [
        'token', 'paid', 'password', 'company_id'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
