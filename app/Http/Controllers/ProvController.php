<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses data user yang sedang login

class ProvController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $currentPage = $request->query('page', 1);
            $search = $request->query('search');

            $provisi = Provinsi::select('*');

            $totalData = $provisi->count();

            $data = $provisi->when($search, function ($query) use ($search) {
                return $query->where('nama_provinsi', 'like', '%' . $search . '%');
            })
            ->paginate($perPage, ['*'], 'page', $currentPage);

            $totalPages = ceil($totalData / $perPage);

            $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $this->logActivity('melihat data provinsi');
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
