<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Appointment Model
 * 
 * This model represents appointments between patients and hospitals.
 * Appointments can be for COVID tests or vaccinations.
 */
class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'hospital_id',
        'type',
        'appointment_date',
        'status',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the hospital that owns the appointment.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the covid test result for this appointment.
     */
    public function covidTestResult(): HasOne
    {
        return $this->hasOne(CovidTestResult::class);
    }

    /**
     * Get the vaccination record for this appointment.
     */
    public function vaccinationRecord(): HasOne
    {
        return $this->hasOne(VaccinationRecord::class);
    }

    /**
     * Check if appointment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if appointment is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if appointment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if appointment is for COVID test
     */
    public function isCovidTest(): bool
    {
        return $this->type === 'covid_test';
    }

    /**
     * Check if appointment is for vaccination
     */
    public function isVaccination(): bool
    {
        return $this->type === 'vaccination';
    }
}
