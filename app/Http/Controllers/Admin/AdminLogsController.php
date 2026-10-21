<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminLogsController extends Controller
{
    /** Journal d'activité : qui a fait quoi, quand (ADM-06) */
    public function index(Request $request)
    {
        $action = $request->query('action');
        $userId = $request->query('user');

        $logs = ActivityLog::with('user')
            ->when($action, fn ($q) => $q->where('action', $action))
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.logs', [
            'logs' => $logs,
            'actionFilter' => $action,
            'userFilter' => $userId,
            'users' => \App\Models\User::orderBy('first_name')->get(['id', 'first_name', 'last_name']),
        ]);
    }
}
