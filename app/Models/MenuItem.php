<?php

namespace App\Models;

use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    protected $fillable = [
        'parent_id',
        'menu_id',
        'title',
        'url',
        'route',
        'target',
        'icon_class',
        'color',
        'order',
        'parameters',
    ];

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
     * @param null|int $parent
     *
     * @return int
     */
    public function highestOrderMenuItem($parent = null)
    {
        $order = 1;
        $item = $this->where('parent_id', '=', $parent)
            ->orderBy('order', 'DESC')
            ->first();

        if ($item !== null) {
            $order = (int) $item->order + 1;
        }

        return $order;
    }

    /**
     * Prepare links for a given url address.
     *
     * @param bool        $absolute
     * @param string|null $route
     * @param mixed|null  $parameters
     * @param string|null $url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    protected function prepareLink($absolute, $route, $parameters, $url = null)
    {
        if ($parameters === null) {
            $parameters = [];
        }

        if (\is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        } elseif (\is_object($parameters)) {
            $parameters = json_decode(json_encode($parameters), true);
        }

        if ($route !== null) {
            if (! Route::has($route)) {
                return '#';
            }

            return route($route, $parameters, $absolute);
        }

        if ($absolute && $url !== null) {
            return url($url);
        }

        return $url;
    }
}
