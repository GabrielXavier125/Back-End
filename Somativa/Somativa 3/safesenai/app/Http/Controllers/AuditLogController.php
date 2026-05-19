<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($action = $request->get('action')) {
            $query->where('action', 'like', "%{$action}%");
        }

        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('audit-logs.index', compact('logs'));
    }
}
