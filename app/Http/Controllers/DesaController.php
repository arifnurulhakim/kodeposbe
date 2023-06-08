<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa;

class DesaController extends Controller
{
    public function index()
    {
        try {
            $desa = Desa::all();

            $desa = Desa::
            select('desas.*','provinsis.nama_provinsi','kabupatens.nama_kabupaten','kecamatans.nama_kecamatan')
            ->leftjoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftjoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftjoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();
            return response()->json([
                'status' => 'success',
                'data' => $desa,
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
