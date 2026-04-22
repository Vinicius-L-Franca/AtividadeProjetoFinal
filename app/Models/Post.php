<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['user_id', 'image_url', 'caption'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}