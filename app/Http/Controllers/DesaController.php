<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 10);
            $currentPage = $request->input('currentPage', 1);
    
            $desa = Desa::select('desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                ->paginate($perPage, ['*'], 'page', $currentPage);
    
            $totalData = $desa->total();
            $totalPage = $desa->lastPage();
    
            return response()->json([
                'status' => 'success',
                'total_data' => $totalData,
                'total_page' => $totalPage,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'data' => $desa->items(),
               
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    
    
    

    public function store(Request $request)
    {
        try {
            $desa = Desa::create($request->all());
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
            return response()->json([
                'status' => 'success',
                'data' => $desa,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
