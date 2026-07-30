<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $guarded = [];

    Public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}