<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Hospital Model
 * 
 * This model represents hospitals in the COVID vaccination system.
 * Hospitals can register, manage appointments, and update patient records.
 */
class Hospital extends Model
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
        'location',
        'phone',
        'email',
        'password',
        'status',
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
     * Get the appointments for this hospital.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the covid test results for this hospital.
     */
    public function covidTestResults(): HasMany
    {
        return $this->hasMany(CovidTestResult::class);
    }

    /**
     * Get the vaccination records for this hospital.
     */
    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    /**
     * Check if hospital is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if hospital is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
