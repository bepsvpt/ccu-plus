<?php

namespace App\Ccu\General;

use App\Ccu\Core\Entity;
use App\Ccu\User\User;

class Comment extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'comment_id', 'commentable_id', 'anonymous', 'commentable_type', 'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['liked'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'comment_id', 'content', 'anonymous', 'likes',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'anonymous' => 'boolean',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * Get the comment's user_id attribute.
     *
     * @return bool
     */
    public function getLikedAttribute()
    {
        if (! \Auth::guard()->check()) {
            return false;
        }

        return $this->likes()->where('user_id', \Auth::guard()->user()->getAuthIdentifier())->exists();
    }

    /**
     * Get the comment's user_id attribute.
     *
     * @param int $value
     * @return int|null
     */
    public function getContentAttribute($value)
    {
        if (is_null($this->getAttribute('deleted_at'))) {
            return $value;
        }

        return null;
    }

    /**
     * Get the comment's user_id attribute.
     *
     * @param int $value
     * @return int|null
     */
    public function getUserIdAttribute($value)
    {
        return $this->getAttribute('anonymous') ? null : $value;
    }

    /**
     * 評論的使用者.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 所評論的教授.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function professors()
    {
        return $this->belongsToMany(Category::class, 'comment_professor', 'comment_id', 'professor_id');
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * 評論的評論.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(static::class);
    }

    /**
     * 評論按讚資料.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
