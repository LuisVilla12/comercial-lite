<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SucursalScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // if (
        //     auth()->check() &&
        //     auth()->user()->tipo === 2 // usuario normal
        // ) {
        //     $builder->where(
        //         $model->getTable() . '.sucursal_id',
        //         auth()->user()->sucursal_id
        //     );
        // }
    }
}
