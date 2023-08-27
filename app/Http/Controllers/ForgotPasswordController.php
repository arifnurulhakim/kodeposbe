<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request; // Import the Request class
use Illuminate\Support\Facades\Validator; // Import the Validator class

class ForgotPasswordController extends Controller
{
    /**
     * Send random code to email of user to reset password (Step 1)
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            ResetCodePassword::where('email', $request->email)->delete();

            $codeData = ResetCodePassword::create([
                'email' => $request->email,
                'code' => rand(100000, 999999), // Replace with your code generation logic
            ]);

            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

            return response()->json(['message' => trans('email sent')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
