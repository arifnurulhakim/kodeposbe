<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class KecController extends Controller
{
    public function index()
    {
        try {
            $kecamatan = Kecamatan::select('kecamatans.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();
                $userLogin = Auth::user();
                if ($userLogin->role == 1) {
                    $userLog = new UserLog();
                    $userLog->user_id = $userLogin->id;
                    $userLog->aktivitas = 'melihat data kecamatan';
                    $userLog->modul = 'KecController';
                    $userLog->save();
                }
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $kecamatan = Kecamatan::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat kecamatan');
            
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            
            // Tambahkan logging aktivitas
            $this->logActivity('melihat kecamatan');
            
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $kecamatan->update($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui kecamatan');
            
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $kecamatan->delete();
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus kecamatan');
            
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
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
            $userLog->modul = 'KecController';
            $userLog->save();
        }
    }
}
