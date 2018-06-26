<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OrderTrait
{
    public function scopeIdAsc(Builder $builder)
    {
        return $builder->orderBy('id', 'asc');
    }

    public function scopeIdDesc(Builder $builder)
    {
        return $builder->orderBy('id', 'desc');
    }

    public function scopeCreateAsc(Builder $builder)
    {
        return $builder->orderBy('created_at', 'asc');
    }

    public function scopeCreateDesc(Builder $builder)
    {
        return $builder->orderBy('created_at', 'desc');
    }

    public function scopeUpdateAsc(Builder $builder)
    {
        return $builder->orderBy('updated_at', 'asc');
    }

    public function scopeUpdateDesc(Builder $builder)
    {
        return $builder->orderBy('updated_at', 'desc');
    }

    public function scopeOrderAsc(Builder $builder)
    {
        return $builder->orderBy('order', 'asc');
    }

    public function scopeOrderDesc(Builder $builder)
    {
        return $builder->orderBy('order', 'desc');
    }
}