<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KodePos;
use App\Models\UserLog; // Tambahkan ini untuk mengakses model UserLog
use Illuminate\Support\Facades\Auth;// Tambahkan ini untuk mengakses data user yang sedang login
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;

class KodePosController extends Controller
{
    public function index()
    {
        try {
        
    
            $kodepos = KodePos::select('kode_pos.*', 'desas.nama_desa', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov');
                $data = $kodepos->get();
                $totalData = KodePos::count();
        
                return response()->json([
                    'status' => 'success',
                    'total_data' => $totalData,
                    'data' => $data,
                ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function dashboard()
    {
        try {
            // Ambil data provinsi
            $kodepos = KodePos::all();
            $jumlahKodepos = $kodepos->count();
    
            $provinsi = Provinsi::all();
            $jumlahProvinsi = $provinsi->count();
    
            // Ambil data kabupaten
            $kabupaten = Kabupaten::all();
            $jumlahKabupaten = $kabupaten->count();
    
            // Ambil data kecamatan
            $kecamatan = Kecamatan::all();
            $jumlahKecamatan = $kecamatan->count();
    
            // Ambil data desa
            $desa = Desa::all();
            $jumlahDesa = $desa->count();
    
            return response()->json([
                'status' => 'success',
                'data' =>[
                    'jumlah_provinsi' => $jumlahProvinsi,
        
                    'jumlah_kabupaten' => $jumlahKabupaten,
      
                    'jumlah_kecamatan' => $jumlahKecamatan,
    
                    'jumlah_desa' => $jumlahDesa,
    
                    'jumlah_kodepos' => $jumlahKodepos
                ]
               

            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    

    public function getbyprovinsi($provinsi){
        try{
            $kodepos = KodePos::select('kabupatens.nama_kabupaten')
            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
            ->where('provinsis.nama_provinsi', $provinsi)
            ->groupBy('kabupatens.nama_kabupaten')
            ->distinct()
            ->get();
         // Mengatur jumlah item per halaman menjadi jumlah total data

                return response()->json([
                    'status' => 'success',
                    'data' => $kodepos, // Mengambil hanya item-datanya saja
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function getbykabupaten($provinsi,$kabupaten){
        try{
            $kodepos = KodePos::select('kecamatans.nama_kecamatan')
            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
            ->where('provinsis.nama_provinsi', $provinsi)
            ->where('kabupatens.nama_kabupaten', $kabupaten)
            ->groupBy('kecamatans.nama_kecamatan')
            ->distinct()
            ->get(); // Mengatur jumlah item per halaman menjadi jumlah total data

                return response()->json([
                    'status' => 'success',
                    'data' => $kodepos, // Mengambil hanya item-datanya saja
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function getbykecamatan($provinsi,$kabupaten,$kecamatan){
        try{
            $kodepos = KodePos::select('desas.nama_desa')
            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
            ->where('provinsis.nama_provinsi',$provinsi)
            ->where('kabupatens.nama_kabupaten',$kabupaten)
            ->where('kecamatans.nama_kecamatan',$kecamatan)
            ->groupBy('desas.nama_desa')
            ->distinct()
            ->get(); // Mengatur jumlah item per halaman menjadi jumlah total data

                return response()->json([
                    'status' => 'success',
                    'data' => $kodepos, // Mengambil hanya item-datanya saja
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function getbydesa($provinsi,$kabupaten,$kecamatan,$desa){
        try{
            $kodeposData = KodePos::select('kode_pos.*', 'desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan',DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),)
            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
            ->where('provinsis.nama_provinsi',$provinsi)
            ->where('kabupatens.nama_kabupaten',$kabupaten)
            ->where('kecamatans.nama_kecamatan',$kecamatan)
            ->where('desas.nama_desa',$desa)
            ->first(); 

            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodeposData->kode_new;
            $qrCode = QrCode::format('png')->size(300)->generate($url);
            $base64 = base64_encode($qrCode);
            
            
            if($kodeposData){
                return response()->json([
                    'status' => 'success',
                    'qrcode'=> $base64,
                     'longitude'=>$kodeposData->longitude,
                    'latitude'=>$kodeposData->latitude,
                    'geojson' => $kodeposData->geojson,
                   
                    'data' => $kodeposData,
                  

                ], 200);
            } else {
                return response()->json(['message' => 'Wilayah not found'], 404);
            }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function getbykodepos($kodepos){
        try{
            $kodepos = trim($kodepos); // Menghapus spasi di awal dan akhir kodepos
            $digitCount = strlen($kodepos); // Menghitung jumlah karakter dalam kodepos
            
            if($digitCount === 5){
                $getkodeposData = KodePos::select(  DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),'kode_pos.*', 'desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                ->where('kode_pos.kode_old', $kodepos)
                ->get();
            
            if ($getkodeposData->isEmpty()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan.'
                ];
            } else {
                $data = $getkodeposData->first()->toArray();
                $desas = $getkodeposData->map(function ($item) {
                    return [
                        'kode_dagri' => $item['kode_dagri'],
                        'kode_mod' => $item['kode_mod'],
                        'kode_new' => $item['kode_new'],
                        'nama_desa' => $item['nama_desa'],
                    ];
                });
                $kodeposData= [
                        'kode_old' => $data['kode_old'],
                        'nama_provinsi' => $data['nama_provinsi'],
                        'nama_kabupaten' => $data['nama_kabupaten'],
                        'nama_kecamatan' => $data['nama_kecamatan'],
                        'desas' => $desas
                ];
            }
            } elseif($digitCount === 7){
                $kodeposData = KodePos::select(
                    'kode_pos.*',
                    'desas.*',
                    'provinsis.nama_provinsi',
                    'kabupatens.nama_kabupaten',
                    'kecamatans.nama_kecamatan',
                    DB::raw("ST_AsGeoJSON(desas.geom) AS geojson")
                )
                    ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                    ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                    ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                    ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                    ->where('kode_pos.kode_new', $kodepos)
                    ->first();
                
            } elseif($digitCount === 10){
                $kodeposData = KodePos::select(  DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),'kode_pos.*', 'desas.*', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                    ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                    ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                    ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                    ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                    ->where('kode_pos.kode_dagri', $kodepos)
                    ->first();
            } else {
                return response()->json(['message' => 'Invalid Kodepos'], 400);
            }
            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodeposData->kode_new;
            $qrCode = QrCode::format('png')->size(300)->generate($url);
            
            // Convert QR code image to base64
            $base64 = base64_encode($qrCode);
            
            
            if($kodeposData){
                return response()->json([
                    'status' => 'success',
                    'qrcode'=> $base64,
                     'longitude'=>$kodeposData->longitude,
                    'latitude'=>$kodeposData->latitude,
                    'geojson' => $kodeposData->geojson,
                   
                    'data' => $kodeposData,
                  

                ], 200);
            } else {
                return response()->json(['message' => 'Kodepos not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    public function getbywilayah()
    {
        try {
            $kodepos = KodePos::select('kode_pos.*', 'desas.nama_desa', 'provinsis.nama_provinsi', 'kabupatens.nama_kabupaten', 'kecamatans.nama_kecamatan')
                ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                // ->where('desas.nama_desa', $wilayah) // Filter based on the given $wilayah
                ->get();
    
            $data = $kodepos->map(function ($item) {
                return [
                    'wilayah' => $item->nama_desa.','. $item->nama_kecamatan.','.$item->nama_kabupaten.','. $item->nama_provinsi
                ];
            });
    
            return response()->json([
                'status' => 'success',
                'data' => $data,
                
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    

    
    public function store(Request $request)
    {
        try {
            $kodepos = KodePos::create($request->all());
            
            // Tambahkan logging aktivitas
            $this->logActivity('membuat kode pos');
            
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
            
            // Tambahkan logging aktivitas
            $this->logActivity('melihat kode pos');
            
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
            
            // Tambahkan logging aktivitas
            $this->logActivity('memperbarui kode pos');
            
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
            
            // Tambahkan logging aktivitas
            $this->logActivity('menghapus kode pos');
            
            return response()->json([
                'status' => 'success',
                'data' => $kodepos,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    // Fungsi untuk logging aktivitas
    private function logActivity($activity)
    {
        $userLogin = Auth::user();
        
        if ($userLogin) {
            $userLog = new UserLog();
            $userLog->user_id = $userLogin->id;
            $userLog->aktivitas = $activity;
            $userLog->modul = 'KodePosController';
            $userLog->save();
        }
    }

    public function generateQrCode($id)
    {
        try {
            $kodepos = KodePos::findOrFail($id);
            
            // Generate QR code from URL
            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodepos->kode_new;
            $qrCode = QrCode::format('png')->size(300)->generate($url);
            
            // Convert QR code image to base64
            $base64 = base64_encode($qrCode);
            $img = [
                'base64' => $base64
            ];
            
            $qrCodeData = [
                'qrCode' => $img,
                'kodeposData' => $kodepos,
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
