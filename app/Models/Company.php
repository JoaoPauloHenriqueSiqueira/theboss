<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    protected $collection = 'companies';
    protected $fillable = ['name', 'cnpj', 'active', 'phone','email','is_api'];

    public function token()
    {
        return $this->hasOne(CompanyTokenActive::class);
    }

    /**
     * Mutator para data
     *
     * @param [type] $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y H:i');
    }


}
