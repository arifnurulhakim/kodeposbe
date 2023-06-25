<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa;

class DesaController extends Controller
{
    public function index(Request $request, $first = null, $last = null)
    {
        try {
            $first = $first ?? $request->input('first', 1);
            $last = $last ?? $request->input('last', 10);
            
            $desa = Desa::select('desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov');
            
            if ($first !== null && $last !== null) {
                $desa->skip($first - 1)->take($last - $first + 1);
            }
            
            $data = $desa->get();
            $totalData = Desa::count();
    
            return response()->json([
                'status' => 'success',
                'total_data' => $totalData,
                'data' => $data,
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
