<?php

namespace App\Models;

use App\Library\Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Categories extends Model
{
    protected $collection = 'categories';
    protected $fillable = ['name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'products_categories', 'category_id', 'product_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });

    }

    
}
