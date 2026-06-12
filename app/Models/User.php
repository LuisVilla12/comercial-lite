<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\Sucursal;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

        use AuditableTrait;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'tipo',
        'estatus',
        'empresa_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // public function sucursal()
    // {
    //     return $this->belongsTo(Sucursal::class);
    // }
    public function isAdmin()
    {
        return $this->tipo == 1;
    }

    public function isNormal()
    {
        return $this->tipo == 2;
    }
    public function empresa()
{
    return $this->belongsTo(Empresa::class);
}
}
