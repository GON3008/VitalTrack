<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiWarning extends Model
{
    use HasFactory;

    protected $table = 'ai_warnings';

    protected $fillable = [
        'ai_analysis_id',
        'indicator_name',
        'level',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationships
    public function aiAnalysis()
    {
        return $this->belongsTo(AiAnalysis::class);
    }

    // Scope
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeDanger($query)
    {
        return $query->where('level', 'danger');
    }
}
