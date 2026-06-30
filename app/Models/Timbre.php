<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timbre extends Model
{

    protected $fillable = [
        'contratados',
        'utilizados',
        'disponibles'
    ];
}
