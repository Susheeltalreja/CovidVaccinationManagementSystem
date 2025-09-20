<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * CovidTestResult Model
 * 
 * This model represents COVID-19 test results for patients.
 * Results can be positive, negative, or inconclusive.
 */
class CovidTestResult extends Model
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
        'result',
        'notes',
        'test_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'test_date' => 'date',
    ];

    /**
     * Get the appointment that owns the test result.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient that owns the test result.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the hospital that owns the test result.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Check if result is positive
     */
    public function isPositive(): bool
    {
        return $this->result === 'positive';
    }

    /**
     * Check if result is negative
     */
    public function isNegative(): bool
    {
        return $this->result === 'negative';
    }

    /**
     * Check if result is inconclusive
     */
    public function isInconclusive(): bool
    {
        return $this->result === 'inconclusive';
    }
}
