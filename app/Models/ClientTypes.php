<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTypes extends Model
{
    protected $collection = 'client_types';
    protected $fillable = ['name', 'doc'];
}
