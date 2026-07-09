<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\Sucursal;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
        use HasRoles;

        protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'tipo',
        'estatus',
        'empresa_id',
        'sucursal_id',
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
        if ($this->tipo==1 or $this->tipo==5){
            return true;
        }
    }
    public function isInventario()
    {
        if ($this->tipo==3 or $this->tipo==4){
            return true;
        }
        return false;
    }

    public function isVendedor()
    {
        if ($this->tipo==2){
        return true;
        }
        return false;
    }
    public function empresa()
{
    return $this->belongsTo(Empresa::class);
}
public function sucursal()
{
    return $this->belongsTo(Sucursal::class);
}
}
