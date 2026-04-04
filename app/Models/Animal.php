<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $guarded = [];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    protected $appends = ['age_display'];

    public function getAgeDisplayAttribute()
    {
        if (!$this->birth_date) return 'Age unknown';
        
        $birthDate = \Carbon\Carbon::parse($this->birth_date);
        $now = \Carbon\Carbon::now();
        
        $diff = $birthDate->diff($now);
        $years = $diff->y;
        $months = $diff->m;
        
        if ($years > 0) {
            return $years . ' ' . \Illuminate\Support\Str::plural('year', $years) . ($months > 0 ? ' ' . $months . ' ' . \Illuminate\Support\Str::plural('month', $months) : '');
        }
        
        return $months . ' ' . \Illuminate\Support\Str::plural('month', $months);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function weights()
    {
        return $this->hasMany(AnimalWeight::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function breedingsAsMother()
    {
        return $this->hasMany(Breeding::class, 'mother_id');
    }

    public function breedingsAsFather()
    {
        return $this->hasMany(Breeding::class, 'father_id');
    }

    public function feedings()
    {
        return $this->hasMany(Feeding::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
}
