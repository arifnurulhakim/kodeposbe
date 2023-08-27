<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ApiKey;
use App\Models\UserLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                    'error_code' => 'INPUT_VALIDATION_ERROR'
                ], 422);
            }
        
            $credentials = $request->only('email', 'password');
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'error_code' => 'USER_NOT_FOUND'
                ], 404);
            }
            $user = Auth::user();
            $apikey = ApiKey::where('user_id',$user->id)->first();
            // dd($user);
        
            // Create user log
            if($user){
                $userLog = new UserLog();
                $userLog->user_id = $user->id;
                $userLog->aktivitas = 'login';
                $userLog->modul = 'auth';
                $userLog->save();
                }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'user' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'instansi' => $user->instansi,
                    'token' => $token,
                    'api_key' => $apikey->key,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'instansi' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                    'error_code' => 'INPUT_VALIDATION_ERROR'
                ], 422);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'instansi' => $request->get('instansi'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
            ]);
            $apiKey = JWTAuth::fromUser($user); // Generate JWT token as API key
            $apiKeyRecord = new ApiKey([
                'key' => $apiKey,
                'user_id' => $user->id,
            ]);
            $apiKeyRecord->save();

            $userlogin = Auth::user();
            if($userlogin){
                $userLog = new UserLog();
                $userLog->user_id = $userlogin->id;
                $userLog->aktivitas = 'membuatkan akun';
                $userLog->modul = 'auth';
                $userLog->save();
                }else{
                    $userLog = new UserLog();
                    $userLog->user_id = $user->id;
                    $userLog->aktivitas = 'register';
                    $userLog->modul = 'auth';
                    $userLog->save();
                }
            $token = JWTAuth::fromUser($user);

          return response()->json([
            'status' => 'success',
            'data'=>[
                'user' => $user,
                'api_key' => $apiKey,
            ]
          ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
    public function generateApiKey()
{
    $user = Auth::user();
    $apiKey = JWTAuth::fromUser($user); 
    $apiKeyRecord = ApiKey::where('user_id', $user->id)->first();

    if ($apiKeyRecord) {
        // Jika API key untuk pengguna sudah ada, update API key yang ada
        $apiKeyRecord->update(['key' => $apiKey]);
    } else {
        // Jika belum ada, tambahkan API key baru
        $apiKeyRecord = new ApiKey([
            'key' => $apiKey,
            'user_id' => $user->id,
        ]);
        $apiKeyRecord->save();
    }

    return response()->json([
        'user' => $user,
        'api_key' => $apiKey,
          ], 200);
}

    public function logout()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized, please login again',
                    'error_code' => 'USER_NOT_FOUND'
                ], 401);
            }
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid token',
                    'error_code' => 'INVALID_TOKEN'
                ], 401);
            }
    
            Auth::logout();
            if(Auth::logout()){
                $userLog = new UserLog();
                $userLog->user_id = $user->id;
                $userLog->aktivitas = 'logout';
                $userLog->modul = 'auth';
                $userLog->save();
                }
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function getProfile()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
    
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'instansi' => $user->instansi,
            ];
    
            return response()->json([
                'status' => 'success',
                'data' => $userData,
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::where('id',$id)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'user not found',
                    'error_code' => 'USER_NOT_FOUND'
                ], 401);
            }
    
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'instansi' => $user->instansi,
            ];
    
            return response()->json([
                'status' => 'success',
                'data' => $userData,
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllUser()
    {
        try {
            $users = User::orderBy('name', 'asc')->get();
            $userArray = [];
            foreach ($users as $user) {
                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'instansi' => $user->instansi,
                ];
                array_push($userArray, $userData);
            }
            return response()->json([
                'status' => 'success',
                'data' => $userArray,
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'error_code' => 'USER_NOT_FOUND'
                ], 404);
            }
            if ($request->has('email') && $request->email === $user->email) {
                $request->request->remove('email');
            }
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'instansi' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users',
                'password' => 'string|min:6',
                'role' => 'integer'
            ]);

            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                    'error_code' => 'INPUT_VALIDATION_ERROR'
                ], 422);
            }

            $user->name = $request->name ?? $user->name;
            $user->role = $request->role ?? $user->role;
            $user->instansi = $request->instansi ?? $user->role;
            $user->email = $request->email ?? $user->email;
            $user->password = $request->password ? bcrypt($request->password) : $user->password;
            $user->save();

            $userlogin = Auth::user();
            if($userlogin){
                $userLog = new UserLog();
                $userLog->user_id = $userlogin->id;
                $userLog->aktivitas = 'mengubah akun';
                $userLog->modul = 'auth';
                $userLog->save();
                }else{
                    $userLog = new UserLog();
                    $userLog->user_id = $user->id;
                    $userLog->aktivitas = 'mengubah akun';
                    $userLog->modul = 'auth';
                    $userLog->save();
                }

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $user = User::find($request->id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'User with name ' . $user->name .' and with email '.$user->email . ' has been deleted.'
                ]);            
                $userlogin = Auth::user();
                if($userlogin){
                    $userLog = new UserLog();
                    $userLog->user_id = $userlogin->id;
                    $userLog->aktivitas = 'mengubah akun';
                    $userLog->modul = 'auth';
                    $userLog->save();
                    }else{
                        $userLog = new UserLog();
                        $userLog->user_id = $user->id;
                        $userLog->aktivitas = 'mengubah akun';
                        $userLog->modul = 'auth';
                        $userLog->save();
                    }
                
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User with name ' . $user->name .' and with email '.$user->email . ' not found.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportCSV()
    {
        try {
            $data = User::select('id', 'name', 'email')->orderBy('name', 'asc')->get(); // query data dari database
            $dateStart = date('Ymd');
            $filename = "users_".$dateStart.".csv";
            //local
            $filename_path = public_path('storage/csv/' . $filename); // path to save CSV file in public/storage/csv
            
            //server
            // $filename_path = '/home/doddiplexus/doddi.plexustechdev.com/templete/api/public/csv/' . $filename;
   
            // buat file CSV
            $handle = fopen($filename_path, 'w');
            fputcsv($handle, ['ID', 'Name', 'Email']);
            foreach($data as $row) {
                fputcsv($handle, [$row->id, $row->name, $row->email]);
            }
            fclose($handle);
    
            // Kasih balikan nama file & urlnya
            $filename_url = url('storage/csv/'.$filename);
            return response()->json([
                'status' => 'SUCCESS',
                'filename' => $filename,
                'filename_url' => $filename_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to export CSV: ' . $e->getMessage()
            ], 500);
        }
    }
    




}

