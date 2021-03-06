<?php

namespace App\Models;

use App\Library\Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

class Clients extends Model
{
    protected $collection = 'clients';
    protected $fillable = ['name', 'birthday', 'password', 'cep', 'city', 'neighborhood', 'complement', 'cpf_cnpj', 'address', 'phone', 'cell_phone', 'email', 'notifiable', 'company_id', 'metadata'];

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function getCellPhoneAttribute($date)
    {
        return Format::formatPhone($date);
    }

    public function getPhoneAttribute($date)
    {
        return Format::formatPhone($date);
    }

    public function getDateBirthdayAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getBirthdaDateFormatAttribute()
    {
        return Carbon::parse($this->attributes['birthday'])->format('Y-m-d');
    }

    public function getFullNameValueAttribute()
    {
        $name = $this->attributes['name'];

        if (Arr::get($this->attributes, "cpf_cnpj")) {
            $name .= ' - Doc: (' . $this->attributes['cpf_cnpj'] . ')';
        }

        return $name;
    }
}
