<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'start_time',
        'end_time',
        'location',
        'status',
        'remarks',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];
}
