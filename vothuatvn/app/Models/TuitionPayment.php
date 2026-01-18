<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tuition_id',
        'student_id',
        'amount',
        'paid_at',
        'status',
        'notes',
    ];

    public function tuition()
    {
        return $this->belongsTo(Tuition::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
