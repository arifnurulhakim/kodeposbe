<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class ProvController extends Controller
{
    public function index()
    {
        try {
            $provisi = Provinsi::all();
            $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $userLog = new UserLog();
                $userLog->user_id = $userLogin->id;
                $userLog->aktivitas = 'melihat data provinsi';
                $userLog->modul = 'ProvController';
                $userLog->save();
            }
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getallprovinsi()
    {
        try {
            $provisi = Provinsi::all();
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $provisi = Provinsi::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat provinsi');
            
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $provisi = Provinsi::findOrFail($id);
            if (!$provisi) {
                return response()->json(['error' => 'Provinsi not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $provisi = Provinsi::findOrFail($id);
            if (!$provisi) {
                return response()->json(['error' => 'Provinsi not found'], 404);
            }

            $provisi->kode_prov = $request->kode_prov;
            $provisi->nama_provinsi = $request->nama_provinsi;

            $provisi->save();
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui provinsi');
            
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $provisi = Provinsi::findOrFail($id);
            $provisi->delete();
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus provinsi');
            
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
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
            $userLog->modul = 'ProvController';
            $userLog->save();
        }
    }
}
