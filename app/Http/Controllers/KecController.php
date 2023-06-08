<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecController extends Controller
{
    public function index()
    {
        try {
            $kecamatan = Kecamatan::
            select('kecamatans.*','provinsis.nama_provinsi','kabupatens.nama_kabupaten')
            ->leftjoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftjoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();
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
            return response()->json([
                'status' => 'success',
                'data' => $kecamatan,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
