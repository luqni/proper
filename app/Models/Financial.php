<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
