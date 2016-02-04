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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'semester_id', 'code', 'department_id', 'name', 'series_id',
    ];

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
        return $this->belongsToMany(Category::class, 'course_professor', 'course_id', 'professor_id');
    }

    /**
     * 課程的評論.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
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
