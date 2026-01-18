<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'status',
    ];

    /**
     * Get all classes belonging to this club
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    /**
     * Get all coaches assigned to this club
     */
    public function coaches()
    {
        return $this->belongsToMany(Coach::class, 'club_coach');
    }

    /**
     * Scope for active clubs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
