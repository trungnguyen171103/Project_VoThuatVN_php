<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'club_id',
        'name',
        'class_code',
        'coach_id',
        'description',
        'max_students',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Boot method to auto-generate class code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($class) {
            if (!$class->class_code) {
                $class->class_code = $class->generateClassCode();
            }
        });
    }

    /**
     * Generate unique class code
     */
    private function generateClassCode()
    {
        $club = Club::find($this->club_id);
        $clubCode = 'CLB' . str_pad($club->id, 3, '0', STR_PAD_LEFT);

        // Get last class number for this club
        $lastClass = self::where('club_id', $this->club_id)
            ->orderBy('id', 'desc')
            ->first();

        $classNumber = $lastClass ? (int) substr($lastClass->class_code, -3) + 1 : 1;

        return $clubCode . '-C' . str_pad($classNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get club relationship
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get coach relationship
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Get students relationship
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id')->withTimestamps();
    }

    /**
     * Get schedules relationship
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    /**
     * Get attendances relationship
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get tuitions relationship
     */
    public function tuitions()
    {
        return $this->hasMany(Tuition::class);
    }

    /**
     * Check if class is active based on end date
     */
    public function getIsActiveAttribute()
    {
        if (!$this->end_date) {
            return $this->status === 'active';
        }
        return $this->end_date >= now() && $this->status === 'active';
    }

    /**
     * Scope for active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope for expiring soon classes (< 7 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(7)]);
    }
}
