<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vaccine Model
 * 
 * This model represents vaccines available in the COVID vaccination system.
 * Vaccines can be available or unavailable and have stock quantities.
 */
class Vaccine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'stock_quantity'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the vaccination records for this vaccine.
     */
    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    /**
     * Check if vaccine is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock_quantity > 0;
    }

    /**
     * Check if vaccine has stock
     */
    public function hasStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
