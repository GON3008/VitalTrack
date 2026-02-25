<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecordItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'medical_record_id',
        'indicator_name',
        'indicator_code',
        'value',
        'unit',
        'normal_min',
        'normal_max',
        'status',
    ];

    protected $casts = [
        'value'      => 'float',
        'normal_min' => 'float',
        'normal_max' => 'float',
    ];

    // Relationships
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Scope lọc chỉ số bất thường
    public function scopeAbnormal($query)
    {
        return $query->whereIn('status', ['low', 'high', 'critical']);
    }

    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }
}
