<?php

namespace App\Models;

use App\Models\Traits\OrderTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use OrderTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'category_id',
        'excerpt',
        'slug',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null                           $order
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOrder(Builder $builder, $order = null)
    {
        switch ($order) {
            case 'recent':
                $builder->createDesc();
                break;
            default:
                $builder->updateDesc();
                break;
        }

        return $builder->with('user', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 话题详情链接。
     *
     * @param array $args
     *
     * @return string
     */
    public function link($args = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $args));
    }
}
