<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLog;

class DashboardController extends Controller
{
    /**
     * Get dashboard data
     *
     * Returns a summary of users and their logs
     *
     * @return \Illuminate\Http\JsonResponse JSON response with dashboard data
     */
    public function resume()
    {
        $users = User::with('logs')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = $users->count();
        $activeUsers = $users->where('status', 'active')->count();
        $inactiveUsers = $users->where('status', 'inactive')->count();

        $groupedUsers = $users->groupBy('role');
        foreach ($groupedUsers as $role => $users) {
            $groupedUsers[$role] = $users->count();
        }

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'roles' => $groupedUsers,
            'logs' => UserLog::with('user')
                ->orderBy('created_at', 'desc')
                // ->where('details->action', '!=', 'deleted')
                ->where('created_at', '>=', now()->subDays(7))
                ->get()
        ]);
    }
}
