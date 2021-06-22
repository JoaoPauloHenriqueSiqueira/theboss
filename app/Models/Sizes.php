<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sizes extends Model
{
    protected $collection = 'sizes';
    protected $fillable = ['name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });

    }
    
}
