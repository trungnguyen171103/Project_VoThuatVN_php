<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'ip_address',
        'user_agent',
        'device',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Get badge color based on action
     */
    public function getActionBadgeAttribute()
    {
        $badges = [
            'login' => 'success',
            'logout' => 'secondary',
            'created' => 'primary',
            'updated' => 'info',
            'deleted' => 'danger',
            'assign_coach' => 'warning',
            'remove_coach' => 'warning',
            'toggle_status' => 'info',
        ];

        return $badges[$this->action] ?? 'secondary';
    }
}
