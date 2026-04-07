<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeding extends Model
{
    protected $guarded = [];

    protected $casts = [
        'fed_at' => 'datetime',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function ration()
    {
        return $this->belongsTo(Ration::class);
    }

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
