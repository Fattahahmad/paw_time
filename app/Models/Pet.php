<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_name',
        'species',
        'breed',
        'gender',
        'birth_date',
        'color',
        'description',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the pet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all growth records for the pet.
     */
    public function growthRecords(): HasMany
    {
        return $this->hasMany(PetGrowth::class);
    }

    /**
     * Get all reminders for the pet.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Get all health checks for the pet.
     */
    public function healthChecks(): HasMany
    {
        return $this->hasMany(HealthCheck::class);
    }

    /**
     * Get all appointments for the pet.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get latest growth record.
     */
    public function latestGrowth()
    {
        return $this->hasOne(PetGrowth::class)->latestOfMany('record_date');
    }

    /**
     * Calculate age from birth_date.
     */
    public function getAgeAttribute(): string
    {
        if (!$this->birth_date) {
            return 'Unknown';
        }

        $birthDate = Carbon::parse($this->birth_date);
        $now = Carbon::now();

        $years = (int) floor($birthDate->floatDiffInYears($now));
        $months = (int) floor($birthDate->copy()->addYears($years)->floatDiffInMonths($now));

        if ($years > 0 && $months > 0) {
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ' . $months . ' month' . ($months > 1 ? 's' : '');
        } elseif ($years > 0) {
            return $years . ' year' . ($years > 1 ? 's' : '');
        } else {
            return $months . ' month' . ($months > 1 ? 's' : '');
        }
    }

    /**
     * Get current weight from latest growth record.
     */
    public function getCurrentWeightAttribute(): ?float
    {
        return $this->latestGrowth?->weight;
    }

    /**
     * Get current height from latest growth record.
     */
    public function getCurrentHeightAttribute(): ?float
    {
        return $this->latestGrowth?->height;
    }

    /**
     * Scope for filtering by species.
     */
    public function scopeSpecies($query, string $species)
    {
        return $query->where('species', $species);
    }

    /**
     * Scope for filtering by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get image URL with proper path.
     */
    public function getImageAttribute(): string
    {
        if ($this->image_url && file_exists(public_path('storage/' . $this->image_url))) {
            return asset('storage/' . $this->image_url);
        }

        // Fallback images based on species
        $fallbacks = [
            'dog' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=400&fit=crop',
            'cat' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=400&h=400&fit=crop',
            'rabbit' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=400&h=400&fit=crop',
            'bird' => 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?w=400&h=400&fit=crop',
        ];

        return $fallbacks[$this->species] ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400&h=400&fit=crop';
    }
}
