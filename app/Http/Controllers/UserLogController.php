<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function index()
    {
        $userLogs = UserLog::join('users', 'users.id', '=', 'user_logs.user_id')
        ->select('user_logs.*', 'users.*')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $userLogs,
        ]);
    }

    public function show($id)
    {
        $userLog = UserLog::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $userLog,
        ]);
    }
}
