<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth;  // Tambahkan ini untuk mengakses data user yang sedang login

class KabController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $currentPage = $request->query('page', 1);
            $search = $request->query('search');

            $kabupaten = Kabupaten::select('kabupatens.*', 'provinsis.nama_provinsi')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov');

            $totalData = $kabupaten->count();

            $data = $kabupaten->when($search, function ($query) use ($search) {
                return $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('kabupatens.nama_kabupaten', 'like', '%' . $search . '%')
                        ->orWhere('provinsis.nama_provinsi', 'like', '%' . $search . '%');
                });
            })
            ->paginate($perPage, ['*'], 'page', $currentPage);

            $totalPages = ceil($totalData / $perPage);

            $userLogin = Auth::user();
            if ($userLogin->role == 1) {
                $this->logActivity('melihat data kabupaten');
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
