<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    protected $cacheExpireInMinutes = 1440;

    public $cacheKey = 'laravelbbs_links';

    public function getAllCaches()
    {
        return Cache::remember($this->cacheKey, $this->cacheExpireInMinutes, function () {
            return $this->all();
        });
    }
}
