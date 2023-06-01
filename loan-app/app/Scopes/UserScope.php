<?php

namespace App\Scopes;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user && UserType::USER->value === $user->type) {
            $builder->where('user_id', $user->id);
        }
    }
}
