<?php

namespace App\Models;

use App\Library\Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Products extends Model
{
    protected $collection = 'products';
    protected $fillable = [
        'name', 'bar_code', 'cost_value',
        'sale_value', 'quantity', 'notifiable',
        'days_notify', 'control_quantity'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sales::class, 'sales_products');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'products_categories', 'product_id', 'category_id');
    }

    public function providers()
    {
        return $this->belongsToMany(Providers::class, 'products_providers', 'product_id', 'provider_id');
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }

    /**
     * Mutator para data
     *
     * @param [type] $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    public function getProfitPercentAttribute()
    {
        $totalCusto = $this->attributes['cost_value'];
        $totalVenda = $this->attributes['sale_value'];
        $lucro =  $totalVenda - $totalCusto;

        if($totalVenda > 0){
            return $this->porcentagem_nx($lucro, $totalVenda) . "%";
        }

        return 0;
    }

    public function getProfitAttribute()
    {
        $totalCusto = $this->attributes['cost_value'];
        $totalVenda = $this->attributes['sale_value'];
        $lucro =  $totalVenda - $totalCusto;
        return  Format::money($lucro);
    }

    public function porcentagem_nx($parcial, $total)
    {
        return number_format(($parcial * 100) / $total, 2);
    }

    public function getSaleFormatValueAttribute()
    {
        return  Format::money($this->attributes['sale_value']);
    }

    public function getSaleFormatValueMoneyAttribute()
    {
        return  Format::moneyWithoutSymbol($this->attributes['sale_value'], '.');
    }

    public function getCostFormatValueAttribute()
    {
        return  Format::money($this->attributes['cost_value']);
    }

    public function getSaleValueAttribute()
    {
        return str_replace(".", ',', $this->attributes['sale_value']);
    }

    public function getCostValueAttribute()
    {
        return str_replace(".", ',', $this->attributes['cost_value']);
    }

    public function getFullNameValueAttribute()
    {
        $name = $this->attributes['name'];

        $name .=  ' -  ' . $this->getSaleFormatValueAttribute();

        if (
            Arr::get($this->attributes, "control_quantity")
        ) {
            $quant = Arr::get($this->attributes, 'quantity');
            if (!$quant) {
                $quant = 0;
            }
            $name .=  ' (qtd:' . $quant . ') ';
        }


        if (Arr::get($this->attributes, "bar_code")) {
            $name .= ' - Cód: (' . $this->attributes['bar_code'] . ')';
        }

        return $name;
    }
}
