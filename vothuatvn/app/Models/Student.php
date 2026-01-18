<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_year',
        'phone',
        'date_of_birth',
        'address',
        'registration_date',
        'parent_name',
        'parent_phone',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_students', 'student_id', 'class_id')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function tuitionPayments()
    {
        return $this->hasMany(TuitionPayment::class);
    }

    /**
     * Scope for active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
