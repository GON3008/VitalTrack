<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAdvice extends Model
{
    use HasFactory;

    protected $table = 'ai_advices';

    protected $fillable = [
        'ai_analysis_id',
        'category',
        'advice',
        'priority',
    ];

    protected $casts = [
        'priority' => 'integer',
    ];

    // Relationships
    public function aiAnalysis()
    {
        return $this->belongsTo(AiAnalysis::class, 'ai_analysis_id');
    }

    // Scope lọc theo category
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
