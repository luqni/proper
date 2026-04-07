<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ration extends Model
{
    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function feeds()
    {
        return $this->belongsToMany(Feed::class, 'ration_feed')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
