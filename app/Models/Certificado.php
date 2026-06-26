<?php

namespace App\Models;


class Certificado extends TenantModel
{
        protected $fillable = [
        'cer_path',
        'key_path',
        'key_password',
    ];
}

