<?php

namespace App\Ccu\General;

use App\Ccu\Core\Entity;
use Cache;

class Category extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['category', 'remark'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category', 'name', 'remark',
    ];

    /**
     * @param string $category
     * @param string $name
     * @param bool $firstId
     * @return \Illuminate\Database\Eloquent\Collection|Category|int|static[]
     */
    public static function getCategories($category = '', $name = '', $firstId = false)
    {
        $categories = Cache::remember('categoriesTable', static::MINUTES_PER_WEEK, function () {
            return static::all();
        });

        if (empty($category)) {
            return $categories;
        }

        $issetName = ! empty($name);

        $categories = $categories->filter(function (Category $item) use ($category, $issetName, $name) {
            $filter = $item->getAttribute('category') === $category;

            return $issetName ? ($filter && $item->getAttribute('name') === $name) : $filter;
        });

        return $firstId
            ? ($categories->count() ? $categories->first()->getAttribute('id') : null)
            : ($issetName ? $categories->first() : $categories->values());
    }
}
