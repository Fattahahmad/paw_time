<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'health_check_id',
        'created_by',
        'diagnosis',
        'treatment',
        'prescription',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function healthCheck()
    {
        return $this->belongsTo(HealthCheck::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getHasAttachmentsAttribute()
    {
        return !empty($this->attachments);
    }

    public function getAttachmentCountAttribute()
    {
        return is_array($this->attachments) ? count($this->attachments) : 0;
    }

    // Mutators
    public function setAttachmentsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['attachments'] = json_encode($value);
        } else {
            $this->attributes['attachments'] = $value;
        }
    }
}
