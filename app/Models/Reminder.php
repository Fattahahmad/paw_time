<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'remind_date',
        'category',
        'repeat_type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'remind_date' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the reminder.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending reminders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for done reminders.
     */
    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    /**
     * Scope for upcoming reminders.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('remind_date', '>=', now())
                     ->where('status', 'pending')
                     ->orderBy('remind_date', 'asc');
    }

    /**
     * Scope for filtering by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for today's reminders.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('remind_date', today());
    }

    /**
     * Mark reminder as done.
     */
    public function markAsDone(): bool
    {
        return $this->update(['status' => 'done']);
    }

    /**
     * Check if reminder is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->remind_date < now();
    }

    /**
     * Get category icon.
     */
    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'feeding' => '🍖',
            'grooming' => '✂️',
            'vaccination' => '💉',
            'medication' => '💊',
            'checkup' => '🩺',
            default => '📝',
        };
    }

    /**
     * Get category color class.
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'feeding' => 'from-orange-400 to-orange-500',
            'grooming' => 'from-purple-400 to-purple-500',
            'vaccination' => 'from-green-400 to-green-500',
            'medication' => 'from-pink-400 to-pink-500',
            'checkup' => 'from-blue-400 to-blue-500',
            default => 'from-gray-400 to-gray-500',
        };
    }
}
