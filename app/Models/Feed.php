<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function refills()
    {
        return $this->hasMany(FeedRefill::class);
    }

    public function rations()
    {
        return $this->belongsToMany(Ration::class, 'ration_feed')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
