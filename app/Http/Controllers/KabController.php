<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\Provinsi;

class KabController extends Controller
{
    public function index()
    {
        try {
            $kabupaten = Kabupaten::select('kabupatens.*','provinsis.nama_provinsi')
            ->leftjoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')->get();
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $kabupaten = Kabupaten::create($request->all());
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
            return response()->json([
                'status' => 'success',
                'data' => $kabupaten,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
