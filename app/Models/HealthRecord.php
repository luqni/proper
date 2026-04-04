<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
