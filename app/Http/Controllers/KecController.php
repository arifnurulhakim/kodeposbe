<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class KecController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $currentPage = $request->query('page', 1);
            $search = $request->query('search');

            $kecamatan = Kecamatan::select('kecamatans.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov');

            $totalData = $kecamatan->count();

            $data = $kecamatan->when($search, function ($query) use ($search) {
                return $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('kecamatans.nama_kecamatan', 'like', '%' . $search . '%')
                        ->orWhere('kabupatens.nama_kabupaten', 'like', '%' . $search . '%')
                        ->orWhere('provinsis.nama_provinsi', 'like', '%' . $search . '%');
                });
            })
            ->paginate($perPage, ['*'], 'page', $currentPage);

            $totalPages = ceil($totalData / $perPage);

            $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $this->logActivity('melihat data kecamatan');
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
