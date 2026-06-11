<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regimen extends Model
{
    protected $table = 'regimenes';
    protected $fillable = [
        'codigo',
        'nombre'
    ];
}
