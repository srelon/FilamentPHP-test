<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoMeta extends Model
{
    use SoftDeletes;

    protected $table = 'seo_meta';

    protected $fillable = [
        'type',
        'record_id',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];
}
