<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Statuses extends Model
{
    protected $collection = 'statuses';
    protected $fillable = ['name','color'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sales::class, 'sales_status', 'status_id', 'sale_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });

    }

    
}
