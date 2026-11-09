<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'category',
        'training_start_date',
        'trainer',
        'status',
        'progress_stage',
        'remarks',
        'completion_date',
    ];

    protected $casts = [
        'training_start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}