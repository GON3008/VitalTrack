<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'examination_date',
        'hospital_name',
        'doctor_name',
        'scan_image_path',
        'status',
    ];

    protected $casts = [
        'examination_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(MedicalRecordItem::class);
    }

    public function aiAnalysis()
    {
        return $this->hasOne(AiAnalysis::class);
    }

    // Scope lọc theo status
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
