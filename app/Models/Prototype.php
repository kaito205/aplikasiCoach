<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prototype extends Model
{
        protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'reminder_time',
        'status',
        'type',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array',
        'start_date' => 'date',
    ];

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }

}
