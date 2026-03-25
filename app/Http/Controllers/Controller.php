<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function ensureOwned(Model $model): void
    {
        abort_unless((int) $model->getAttribute('user_id') === (int) auth()->id(), 404);
    }

    protected function resolveOwnedModel(Request $request, string $routeParameter, string $modelClass): Model
    {
        $model = $modelClass::query()
            ->whereKey($request->route($routeParameter))
            ->when(
                method_exists($modelClass, 'scopeOwnedBy'),
                fn (Builder $query) => $query->ownedBy($request->user())
            )
            ->firstOrFail();

        $this->ensureOwned($model);

        return $model;
    }
}
