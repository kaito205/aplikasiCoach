<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $fillable = [
        'prototype_id',
        'user_id',
        'status',
        'value',
        'logged_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'logged_at' => 'datetime',
    ];
}
