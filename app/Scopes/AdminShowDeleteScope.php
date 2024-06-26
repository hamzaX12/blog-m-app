<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AdminShowDeleteScope implements Scope
{

    public function  apply(Builder $builder, Model $model)
    {
        // $builder->orderBy('updated_at','asc');
        // $builder->orderBy('updated_at','asc');
        if (Auth::check() && Auth::user()->is_admin) {
            $builder->withTrashed();
        }
    }
}
