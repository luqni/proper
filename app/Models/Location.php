<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
