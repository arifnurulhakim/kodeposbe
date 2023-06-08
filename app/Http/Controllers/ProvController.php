<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;

class ProvController extends Controller
{
    public function index()
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
            $provisi = Provinsi::findOrFailOrFail($id);
            $provisi->delete();
            return response()->json([
                'status' => 'success',
                'data' => $provisi,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
