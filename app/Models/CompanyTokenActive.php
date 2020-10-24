<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTokenActive extends Model
{
    protected $collection = 'company_token_active';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attempts', 'max_attempts', 'company_id', 'token', 'api_token'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
