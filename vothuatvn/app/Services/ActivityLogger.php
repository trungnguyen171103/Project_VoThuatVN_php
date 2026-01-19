<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity
     *
     * @param string $action Action type (login, created, updated, deleted, etc.)
     * @param string $description Human-readable description
     * @param string|null $model Model class name
     * @param int|null $modelId Model ID
     * @return ActivityLog
     */
    public static function log($action, $description, $model = null, $modelId = null)
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device' => self::parseDevice(request()->userAgent()),
        ]);
    }

    /**
     * Parse device type from user agent
     *
     * @param string|null $userAgent
     * @return string
     */
    private static function parseDevice($userAgent)
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        // Check for mobile devices
        if (preg_match('/(android|iphone|ipad|mobile)/i', $userAgent)) {
            if (preg_match('/(ipad|tablet)/i', $userAgent)) {
                return 'Tablet';
            }
            return 'Mobile';
        }

        return 'Desktop';
    }

    /**
     * Get action display name in Vietnamese
     *
     * @param string $action
     * @return string
     */
    public static function getActionName($action)
    {
        $actions = [
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'created' => 'Tạo mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
            'assign_coach' => 'Phân quyền HLV',
            'remove_coach' => 'Xóa quyền HLV',
            'toggle_status' => 'Thay đổi trạng thái',
            'payment' => 'Thanh toán',
            'attendance' => 'Điểm danh',
        ];

        return $actions[$action] ?? ucfirst($action);
    }
}
