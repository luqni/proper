<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalWeight extends Model
{
    protected $guarded = [];

    protected $casts = [
        'measured_at' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
