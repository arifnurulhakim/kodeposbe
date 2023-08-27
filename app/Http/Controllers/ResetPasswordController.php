<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if (!$passwordReset) {
            return response()->json([
                'status' => 'error',
                'message' => trans('Invalid reset code')
            ], 422);
        }
        
        if ($passwordReset->isExpire()) {
            return response()->json([
                'status' => 'error',
                'message' => trans('code is expired')
            ], 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => trans('User not found')
            ], 404);
        }

        $user->update([
            "password" => Hash::make($request->password)
        ]);

        $passwordReset->where('code', $request->code)->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('password has been successfully reset')
        ], 200);
    }

    public function resetFirstPassword(Request $request)
    {
        $user = Auth::user();

        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => trans('Unauthorized, please login again')
            ], 422);
        }
        
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => trans('old password not matched')
            ], 422);
        }

        $user->update([
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => trans('password has been successfully reset')
        ], 200);
    }
}
