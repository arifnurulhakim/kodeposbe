<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth;  // Tambahkan ini untuk mengakses data user yang sedang login

class KabController extends Controller
{
    public function index()
    {
        try {

            $kabupaten = Kabupaten::select('kabupatens.*', 'provinsis.nama_provinsi')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();

               $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $userLog = new UserLog();
                $userLog->user_id = $userLogin->id;
                $userLog->aktivitas = 'melihat data kabupaten';
                $userLog->modul = 'KabController';
                $userLog->save();
            }
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $kabupaten = Kabupaten::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat kabupaten');
            
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $kabupaten = Kabupaten::findOrFail($id);
            if (!$kabupaten) {
                return response()->json(['error' => 'kabupaten not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kabupaten = Kabupaten::findOrFail($id);
            $kabupaten->update($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui kabupaten');
            
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kabupaten = Kabupaten::findOrFail($id);
            $kabupaten->delete();
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus kabupaten');
            
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    // Fungsi untuk mencatat aktivitas
    private function logActivity($aktivitas)
    {
        $userLogin = Auth::user();
        if ($userLogin) {
            $userLog = new UserLog();
            $userLog->user_id = $userLogin->id;
            $userLog->aktivitas = $aktivitas;
            $userLog->modul = 'KabController';
            $userLog->save();
        }
    }
}
