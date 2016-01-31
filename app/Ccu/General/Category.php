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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category', 'value', 'remark',
    ];

    /**
     * @param string $category
     * @param string $name
     * @param bool $firstId
     * @return \Illuminate\Database\Eloquent\Collection|int|static[]
     */
    public static function getCategories($category = '', $name = '', $firstId = false)
    {
        /** @var $categories \Illuminate\Database\Eloquent\Collection|static[] */

        $categories = Cache::remember('categoriesTable', static::MINUTES_PER_WEEK, function () {
            return static::all();
        });

        if (empty($category)) {
            return $categories;
        }

        $issetName = ! empty($name);

        $categories = $categories->filter(function (Category $item) use ($category, $issetName, $name) {
            $filter = $item->getAttribute('category') === $category;

            return $issetName ? ($filter && $item->getAttribute('value') === $name) : $filter;
        });

        return $firstId
            ? $categories->first()->getAttribute('id')
            : ($issetName ? $categories->first() : $categories->values());
    }
}
