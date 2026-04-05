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

    protected $appends = ['age_display', 'average_daily_gain', 'inbreeding_risk'];

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
        
    public function getAverageDailyGainAttribute()
    {
        $weights = $this->weights()->orderBy('measured_at', 'desc')->take(2)->get();
        
        if ($weights->count() < 2) return 0;
        
        $current = $weights[0];
        $previous = $weights[1];
        
        $weightDiff = $current->weight - $previous->weight;
        $daysDiff = \Carbon\Carbon::parse($current->measured_at)->diffInDays(\Carbon\Carbon::parse($previous->measured_at));
        
        if ($daysDiff <= 0) return 0;
        
        return round($weightDiff / $daysDiff, 2);
    }

    public function getInbreedingRiskAttribute()
    {
        if (!$this->sire_id || !$this->dam_id) return null;
        
        return self::checkInbreeding($this->sire_id, $this->dam_id);
    }

    public static function checkInbreeding($sireId, $damId)
    {
        if (!$sireId || !$damId) return null;
        if ($sireId == $damId) return 'Same Animal (Self)';

        $sire = self::find($sireId);
        $dam = self::find($damId);

        if (!$sire || !$dam) return null;

        // Parent-Offspring
        if ($sire->sire_id == $damId || $sire->dam_id == $damId) return 'Parent-Offspring (Sire is offspring of Dam)';
        if ($dam->sire_id == $sireId || $dam->dam_id == $sireId) return 'Parent-Offspring (Dam is offspring of Sire)';

        // Siblings
        $sameSire = ($sire->sire_id && $dam->sire_id && $sire->sire_id == $dam->sire_id);
        $sameDam = ($sire->dam_id && $dam->dam_id && $sire->dam_id == $dam->dam_id);

        if ($sameSire && $sameDam) return 'Full Siblings';
        if ($sameSire || $sameDam) return 'Half Siblings';

        // Grandparents (optional but good)
        // For now, let's stick to these primary ones.
        
        return null;
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

    public function sire()
    {
        return $this->belongsTo(Animal::class, 'sire_id');
    }

    public function dam()
    {
        return $this->belongsTo(Animal::class, 'dam_id');
    }

    public function offspring()
    {
        return $this->hasMany(Animal::class, 'sire_id')->orWhere('dam_id', $this->id);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
}
