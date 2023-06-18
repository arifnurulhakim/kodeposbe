<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class ProvController extends Controller
{
    const PER_PAGE = 10;
    const CURRENT_PAGE = 1;
    
    public function index(Request $request)
    {
        try {
            $perPage = $request->perPage ?: self::PER_PAGE;
            $currentPage = $request->currentPage ?: self::CURRENT_PAGE;
            $provisi = Provinsi::paginate($perPage, ['*'], 'page', $currentPage);
            $provisi->appends(['perPage' => $perPage, 'currentPage' => $currentPage]);
    
            $provisiData = $provisi->items();
    
            return response()->json([
                'status' => 'success',
                'total_pages' => $provisi->lastPage(),
                'current_page' => $provisi->currentPage(),
                'per_page' => $provisi->perPage(),
                'total_data' => $provisi->total(), // Menambahkan total data
                'data' => $provisiData,
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
