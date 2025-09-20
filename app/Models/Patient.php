<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Patient Model
 * 
 * This model represents patients in the COVID vaccination system.
 * Patients can register, book appointments, and view their test/vaccination records.
 */
class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the appointments for this patient.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the covid test results for this patient.
     */
    public function covidTestResults(): HasMany
    {
        return $this->hasMany(CovidTestResult::class);
    }

    /**
     * Get the vaccination records for this patient.
     */
    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    /**
     * Check if patient is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get patient's age
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
}
