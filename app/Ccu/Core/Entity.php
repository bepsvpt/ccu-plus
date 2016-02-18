<?php

namespace App\Ccu\Core;

use Eloquent;

class Entity extends Eloquent
{
    const MINUTES_QUARTER_DAY = 360;
    const MINUTES_HALF_DAY = 720;
    const MINUTES_PER_DAY = 1440;
    const MINUTES_PER_WEEK = 10080;
    const MINUTES_PER_MONTH = 43200;

    /**
     * CCU Plus Version.
     */
    const VERSION = '1.0.2';

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Get the table name of this model.
     *
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
