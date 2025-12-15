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
        'status'
    ];

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }

}
