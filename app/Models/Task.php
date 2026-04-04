<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'reminder_sent' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
