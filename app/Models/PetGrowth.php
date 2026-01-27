<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetGrowth extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'pet_growth';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    protected $fillable = [
        'pet_id',
        'weight',
        'height',
        'record_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the pet that owns the growth record.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Scope for ordering by record date.
     */
    public function scopeLatestRecord($query)
    {
        return $query->orderBy('record_date', 'desc');
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('record_date', [$startDate, $endDate]);
    }
}
