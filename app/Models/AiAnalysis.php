<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $table = 'ai_analyses';

    protected $fillable = [
        'medical_record_id',
        'user_id',
        'summary',
        'health_score_level',
        'health_score',
        'trend_data',
        'compared_with_previous',
    ];

    protected $casts = [
        'health_score'           => 'float',
        'trend_data'             => 'array', // JSON tự động decode
        'compared_with_previous' => 'array', // JSON tự động decode
    ];

    // Relationships
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warnings()
    {
        return $this->hasMany(AiWarning::class);
    }

    public function advices()
    {
        return $this->hasMany(AiAdvice::class);
    }
}
