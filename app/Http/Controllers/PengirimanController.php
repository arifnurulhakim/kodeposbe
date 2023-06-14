<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengiriman;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PengirimanController extends Controller
{
    public function index()
    {
        try {
            $pengiriman = Pengiriman::all();
            return response()->json([
                'status' => 'success',
                'data' => $pengiriman,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $pengiriman = Pengiriman::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $pengiriman,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $pengiriman = Pengiriman::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $pengiriman,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pengiriman = Pengiriman::findOrFail($id);
            $pengiriman->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $pengiriman,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pengiriman = Pengiriman::findOrFail($id);
            $pengiriman->delete();
            return response()->json([
                'status' => 'success',
                'data' => $pengiriman,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    
public function generateQrCode($id)
{
    try {
        $pengiriman = Pengiriman::findOrFail($id);
        
        // Generate QR code from URL
        $url = 'https://sistemkodeposkominfo.com/index.html#/Dashboard';
        $qrCode = QrCode::size(300)->generate($url);
        
        // Convert QR code image to base64
        $base64 =base64_encode($qrCode);
        $img = [
            'base64'=>$base64
        ];
        
        $qrCodeData = [
            'qrCode' => $img ,
            'pengirimanData' => $pengiriman,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $qrCodeData,
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}
    
    
}
