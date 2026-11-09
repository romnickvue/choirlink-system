<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthday',
        'contact_number',
        'prk_group_id',
        'tungkulin',
        'status',
        'profile_photo',
        'remarks',
        'date_registered',
    ];

    protected $casts = [
        'birthday' => 'date',
        'date_registered' => 'date',
    ];

    public function prkGroup(): BelongsTo
    {
        return $this->belongsTo(PrkGroup::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name .
            ($this->suffix ? ' ' . $this->suffix : '')
        );
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birthday)->age;
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(MemberTraining::class);
    }
}