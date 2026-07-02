<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'record_id',
        'user_id',
        'parent_id',
        'replied_to_comment_id',
        'rating',
        'body',
        'status',
        'deleted_by',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo('reviewable', 'type', 'record_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ReviewLike::class, 'review_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReviewReport::class, 'review_id');
    }
}
