<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function breedings()
    {
        return $this->hasMany(Breeding::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function financials()
    {
        return $this->hasMany(Financial::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function rations()
    {
        return $this->hasMany(Ration::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function feedings()
    {
        return $this->hasMany(Feeding::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
