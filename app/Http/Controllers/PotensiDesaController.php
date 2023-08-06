<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PotensiDesa;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class PotensiDesaController extends Controller
{
    public function index()
    {
        try {
            $potensidesa = PotensiDesa::join('desas', 'potensi_desas.kode_dagri', '=', 'desas.kode_dagri')
            ->select('desas.nama_desa','potensi_desas.*')
            ->get();
            $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $userLog = new UserLog();
                $userLog->user_id = $userLogin->id;
                $userLog->aktivitas = 'melihat data Potensi Desa';
                $userLog->modul = 'PotensiDesaController';
                $userLog->save();
            }
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getallprovinsi()
    {
        try {
            $potensidesa = PotensiDesa::all();
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $potensidesa = PotensiDesa::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat potensi desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $potensidesa = PotensiDesa::findOrFail($id);
            if (!$potensidesa) {
                return response()->json(['error' => 'PotensiDesa not found'], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $potensidesa = PotensiDesa::findOrFail($id);
            if (!$potensidesa) {
                return response()->json(['error' => 'PotensiDesa not found'], 404);
            }

            $potensidesa->kode_prov = $request->kode_prov;
            $potensidesa->nama_provinsi = $request->nama_provinsi;

            $potensidesa->save();
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui potensi desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $potensidesa = PotensiDesa::findOrFail($id);
            $potensidesa->delete();
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus potensi desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $potensidesa,
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
            $userLog->modul = 'PotensiDesaController';
            $userLog->save();
        }
    }
}
