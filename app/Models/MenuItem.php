<?php

namespace App\Models;

use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'menu_items';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Admin::getModelClass('Menu'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Admin::getModelClass('MenuItem'), 'parent_id')
            ->with('children');
    }

    /**
     * @param bool $absolute
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function link($absolute = false)
    {
        return $this->prepareLink($absolute, $this->route, $this->parameters, $this->url);
    }

    /**
     * @param bool   $absolute
     * @param string $route
     * @param mixed  $parameters
     * @param string $url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    protected function prepareLink($absolute, $route, $parameters, $url)
    {
        if ($parameters === null) {
            $parameters = [];
        }

        if (\is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        } elseif (\is_array($parameters)) {
            $parameters = $parameters;
        } elseif (\is_object($parameters)) {
            $parameters = json_decode(json_encode($parameters), true);
        }

        if ($route !== null) {
            if (! Route::has($route)) {
                return '#';
            }

            return route($route, $parameters, $absolute);
        }

        if ($absolute) {
            return url($url);
        }

        return $url;
    }
}
