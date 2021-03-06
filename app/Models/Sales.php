<?php

namespace App\Models;

use App\Library\Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Sales extends Model
{
    protected $collection = 'sales';
    protected $fillable = [
        'client_id', 'company_id', 'date_sale',
        'time_sale', 'user', 'amount_total',
        'company_id', 'user_id'
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

    public function status()
    {
        return $this->belongsToMany(Statuses::class, 'sales_status', 'sale_id', 'status_id');
    }


    public function products()
    {
        return $this->belongsToMany(Products::class, 'sales_products', 'sale_id', 'product_id')
            ->withPivot('id')
            ->withPivot('quantity')
            ->withPivot('sale_value')
            ->withPivot('sale_id')
            ->withPivot('product_id')
            ->withPivot('size_id');
    }

    public function getDateSaleAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    public function getDateSaleNormalAttribute()
    {
        return $this->attributes['date_sale'];
    }

    public function getDateSaleNormalFinishAttribute($duration)
    {
        return Carbon::parse($this->attributes['date_sale'])->addMinutes($duration)->format('Y-m-d h:i:s');
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

    public function getTitleAttribute($client)
    {
       return $client->name . ' - ' . $client->cell_phone;
    }


}
