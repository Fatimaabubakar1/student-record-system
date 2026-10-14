<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'department',
        'score',
    ];

    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->score >= 91 => 'A+',
            $this->score >= 86 => 'A',
            $this->score >= 81 => 'A-',
            $this->score >= 76 => 'B+',
            $this->score >= 71 => 'B',
            $this->score >= 66 => 'B-',
            $this->score >= 61 => 'C+',
            $this->score >= 50 => 'C',
            $this->score >= 45 => 'D',
            default => 'F',
        };
    }

    public function getGradePointsAttribute(): float
    {
        return match ($this->grade) {
            'A+' => 4.00,
            'A'  => 3.80,
            'A-' => 3.67,
            'B+' => 3.33,
            'B'  => 3.00,
            'B-' => 2.67,
            'C+' => 2.33,
            'C'  => 2.00,
            'D'  => 1.88,
            default => 0.00,
        };
    }

    public function getRemarksAttribute(): string
    {
        return match ($this->grade) {
            'A+' => 'EXCEPTIONAL / HIGH DISTINCTION',
            'A', 'A-' => 'DISTINCTION',
            'B+', 'B', 'B-' => 'MERIT',
            'C+', 'C' => 'PASS',
            'D' => 'REMEDIAL',
            default => 'FAIL',
        };
    }
}
