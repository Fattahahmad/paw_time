<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthCheck extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'health_checks';

    /**
     * Enable timestamps
     */
    public $timestamps = true;

    protected $fillable = [
        'pet_id',
        'check_date',
        'complaint',
        'status',
        'doctor_name',
        'doctor_phone',
        'diagnosis',
        'treatment',
        'prescription',
        'notes',
        'next_visit_date',
    ];

    protected function casts(): array
    {
        return [
            'check_date' => 'date',
            'next_visit_date' => 'date',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the pet that owns the health check.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get the medical record associated with this health check.
     */
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    /**
     * Scope for ordering by check date.
     */
    public function scopeLatestCheck($query)
    {
        return $query->orderBy('check_date', 'desc');
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('check_date', [$startDate, $endDate]);
    }

    /**
     * Check if follow-up is needed.
     */
    public function getNeedsFollowUpAttribute(): bool
    {
        return $this->next_visit_date && $this->next_visit_date >= today();
    }

    /**
     * Check if follow-up is overdue.
     */
    public function getIsFollowUpOverdueAttribute(): bool
    {
        return $this->next_visit_date && $this->next_visit_date < today();
    }
}
