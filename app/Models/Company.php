<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    protected $collection = 'companies';
    protected $fillable = ['name', 'cnpj', 'active', 'phone','email','is_api', 'sale_value', 'view_calendar','control_sale_status','status_id'];

    public function token()
    {
        return $this->hasOne(CompanyTokenActive::class);
    }


    public function payments()
    {
        return $this->hasMany(PaymentCompany::class);
    }

}
