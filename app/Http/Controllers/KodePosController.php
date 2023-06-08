<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KodePos;

class KodePosController extends Controller
{
    public function index()
    {
        try {
           
            $kodepos = KodePos::
            select('kode_pos.*','desas.nama_desa','provinsis.nama_provinsi','kabupatens.nama_kabupaten','kecamatans.nama_kecamatan')
           ->leftjoin('desas', 'kode_pos.kode_desa', '=', 'desas.kode_desa')
            ->leftjoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftjoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftjoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();
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
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
