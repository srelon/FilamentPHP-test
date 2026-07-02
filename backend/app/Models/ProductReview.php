<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'user_id',
        'parent_id',
        'replied_to_comment_id',
        'rating',
        'body',
        'status',
        'deleted_by',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProductReviewLike::class, 'review_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProductReviewReport::class, 'review_id');
    }
}
