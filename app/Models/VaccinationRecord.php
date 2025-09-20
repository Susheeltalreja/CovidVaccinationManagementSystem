<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VaccinationRecord Model
 * 
 * This model represents vaccination records for patients.
 * Records include vaccine type, dose number, and vaccination date.
 */
class VaccinationRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'hospital_id',
        'vaccine_id',
        'dose_number',
        'vaccination_date',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vaccination_date' => 'date',
    ];

    /**
     * Get the appointment that owns the vaccination record.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient that owns the vaccination record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the hospital that owns the vaccination record.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the vaccine that owns the vaccination record.
     */
    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }

    /**
     * Check if this is the first dose
     */
    public function isFirstDose(): bool
    {
        return $this->dose_number === '1';
    }

    /**
     * Check if this is the second dose
     */
    public function isSecondDose(): bool
    {
        return $this->dose_number === '2';
    }

    /**
     * Check if this is the third dose
     */
    public function isThirdDose(): bool
    {
        return $this->dose_number === '3';
    }

    /**
     * Check if this is a booster dose
     */
    public function isBoosterDose(): bool
    {
        return $this->dose_number === 'booster';
    }
}
