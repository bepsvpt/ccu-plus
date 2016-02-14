<?php

namespace App\Ccu;

use App\Ccu\Core\Entity;
use App\Ccu\General\Attachment;
use App\Ccu\General\Category;
use App\Ccu\General\Comment;

class Course extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

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
    protected $hidden = ['id', 'semester_id', 'department_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['new'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'semester_id', 'code', 'department_id', 'name',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['department'];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected static $replacePrimaryKey;

    /**
     * Set the primary key for the model.
     *
     * @param $primaryKey
     */
    public static function setPrimaryKey($primaryKey)
    {
        self::$replacePrimaryKey = $primaryKey;
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        if (! is_null(self::$replacePrimaryKey)) {
            return self::$replacePrimaryKey;
        }

        return $this->primaryKey;
    }

    /**
     * Get the comment's user_id attribute.
     *
     * @return bool
     */
    public function getNewAttribute()
    {
        static $latestSemesterId = null;

        if (is_null($latestSemesterId)) {
            $latestSemesterId = static::max('semester_id');
        }

        return $latestSemesterId === $this->getAttribute('semester_id');
    }

    /**
     * 課程所屬學期
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function semester()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 課程所屬系所
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 通識課程的向度.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dimension()
    {
        return $this->belongsToMany(Category::class, 'course_dimension', 'course_id', 'dimension_id');
    }

    /**
     * 課程授課教授.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function professors()
    {
        return $this->belongsToMany(Category::class, 'course_professor', 'course_id', 'professor_id')
            ->withPivot(['class', 'credit']);
    }

    /**
     * 課程的評論.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable', null, null, 'code');
    }

    /**
     * 課程的考古題.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function exams()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}
