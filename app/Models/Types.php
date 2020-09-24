<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $collection = 'types';
    protected $fillable = ['title', 'is_admin'];

    public function task()
    {
        return $this->hasMany(Tasks::class, 'type_id', 'id');
    }

    public function isAdmin()
    {
        return $this->getAttribute('is_admin');
    }
}
