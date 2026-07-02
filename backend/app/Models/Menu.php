<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Concern\ModelTree;

class Menu extends Model
{
    use ModelTree;

    protected $fillable = [
        'name',
        'route',
        'parent_id',
        'type',
        'params',
        'sort',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'params' => 'array',
        ];
    }
}
