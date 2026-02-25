<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'height',
        'weight',
        'blood_type',
        'chronic_diseases',
        'allergies',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'height'        => 'float',
        'weight'        => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor tính BMI tự động
    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightM = $this->height / 100;
            return round($this->weight / ($heightM * $heightM), 2);
        }
        return null;
    }
}
