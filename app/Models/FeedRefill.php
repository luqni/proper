<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedRefill extends Model
{
    protected $guarded = [];

    protected $casts = [
        'refilled_at' => 'datetime',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
