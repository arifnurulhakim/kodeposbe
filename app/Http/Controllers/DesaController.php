<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KodePos;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth;// Tambahkan ini untuk mengakses data user yang sedang login
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\PotensiDesa;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $currentPage = $request->query('page', 1);
            $search = $request->query('search');
    
            $desa = Desa::select('desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov');
    
            $totalData = $desa->count();
    
            $data = $desa->when($search, function ($query) use ($search) {
                return $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('desas.nama_desa', 'like', '%' . $search . '%')
                        ->orWhere('kecamatans.nama_kecamatan', 'like', '%' . $search . '%')
                        ->orWhere('kabupatens.nama_kabupaten', 'like', '%' . $search . '%')
                        ->orWhere('provinsis.nama_provinsi', 'like', '%' . $search . '%');
                    });
                })
                ->paginate($perPage, ['*'], 'page', $currentPage);            
            $totalPages = ceil($totalData / $perPage);
    
            $userLogin = Auth::user();
    
            if ($userLogin->role == 1) {
                $userLog = new UserLog();
                $userLog->user_id = $userLogin->id;
                $userLog->aktivitas = 'melihat data desa';
                $userLog->modul = 'DesaController';
                $userLog->save();
            }
    
            return response()->json([
                'status' => 'success',
                'total_data' => $totalData,
                'total_pages' => $totalPages,
                'data' => [
                    'current_page' => $data->currentPage(),
                    'data' => $data->items(),
                    'first_page_url' => $data->url(1),
                    'from' => $data->firstItem(),
                    'last_page' => $data->lastPage(),
                    'last_page_url' => $data->url($data->lastPage()),
                    'next_page_url' => $data->nextPageUrl(),
                    'path' => $data->url($data->currentPage()),
                    'per_page' => $data->perPage(),
                    'prev_page_url' => $data->previousPageUrl(),
                    'to' => $data->lastItem(),
                    'total' => $totalData,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $desa = Desa::create($request->all());
            $this->logActivity('membuat desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $desa,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $desa = Desa::findOrFail($id);
            $this->logActivity('melihat desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $desa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $desa = Desa::findOrFail($id);
            $desa->update($request->all());
            $this->logActivity('memperbarui desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $desa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $desa = Desa::findOrFail($id);
            $desa->delete();
            $this->logActivity('menghapus desa');
            
            return response()->json([
                'status' => 'success',
                'data' => $desa,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    private function logActivity($activity)
    {
        $userLogin = Auth::user();
        
        if ($userLogin) {
            $userLog = new UserLog();
            $userLog->user_id = $userLogin->id;
            $userLog->aktivitas = $activity;
            $userLog->modul = 'DesaController';
            $userLog->save();
        }
    }
    
}
