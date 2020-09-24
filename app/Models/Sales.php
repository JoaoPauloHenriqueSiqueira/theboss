<?php

namespace App\Models;

use App\Library\Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Sales extends Model
{
    protected $collection = 'sales';
    protected $fillable = [
        'client_id', 'company_id', 'date_sale',
        'time_sale', 'user', 'amount_total', 'amount_paid'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function products()
    {
        return $this->belongsToMany(Products::class, 'sales_products', 'sale_id', 'product_id')
        ->withPivot('id')
            ->withPivot('quantity')
            ->withPivot('sale_value')
            ->withPivot('sale_id')
            ->withPivot('product_id');;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->user_id = Auth::user()->id;
            $query->company_id = Auth::user()->company_id;
        });
    }

    public function getDateSaleAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    public function getSaleDateFormatAttribute()
    {
        return Carbon::parse($this->attributes['date_sale'])->format('Y-m-d');
    }

    public function getSaleTimeFormatAttribute()
    {
        return Carbon::parse($this->attributes['date_sale'])->format('H:i');
    }
   
    public function getAmountTotalValueAttribute()
    {
        return  Format::money($this->attributes['amount_total']);
    }

    public function getAmountPaidValueAttribute()
    {
        return  Format::money($this->attributes['amount_paid']);
    }

    public function getDebtAttribute()
    {
        return  Format::money($this->attributes['amount_total'] - $this->attributes['amount_paid']);
    }
}
