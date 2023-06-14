<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KodePos;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth;// Tambahkan ini untuk mengakses data user yang sedang login

class KodePosController extends Controller
{
    public function index()
    {
        try {
            $kodepos = KodePos::select('kode_pos.*', 'desas.nama_desa', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('desas', 'kode_pos.kode_desa', '=', 'desas.kode_desa')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();

            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $kodepos = KodePos::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat kode pos');
            
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $kodepos = KodePos::findOrFail($id);
            
            // Tambahkan logging aktivitas
            $this->logActivity('melihat kode pos');
            
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kodepos = KodePos::findOrFail($id);
            $kodepos->update($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui kode pos');
            
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kodepos = KodePos::findOrFail($id);
            $kodepos->delete();
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus kode pos');
            
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    // Fungsi untuk logging aktivitas
    private function logActivity($activity)
    {
        $userLogin = Auth::user();
        
        if ($userLogin) {
            $userLog = new UserLog();
            $userLog->user_id = $userLogin->id;
            $userLog->aktivitas = $activity;
            $userLog->modul = 'KodePosController';
            $userLog->save();
        }
    }
}
