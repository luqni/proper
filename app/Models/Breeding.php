<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breeding extends Model
{
    protected $guarded = [];

    protected $casts = [
        'mating_date' => 'date',
        'due_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mother_id');
    }

    public function father()
    {
        return $this->belongsTo(Animal::class, 'father_id');
    }
}
