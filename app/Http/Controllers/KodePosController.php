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
use App\Models\PotensiDesa;

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
            $provinsi = strtoupper(trim($provinsi));
            $kabupaten = strtoupper(trim($kabupaten));
            $kecamatan = strtoupper(trim($kecamatan));
            $desa = strtoupper(trim($desa));            
       
            $kodeposData = KodePos::select( DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),
            'kode_pos.*',
            'desas.*',
            'provinsis.nama_provinsi',
            'kabupatens.nama_kabupaten',
            'kecamatans.nama_kecamatan',
            'potensi_desas.jumlah_penduduk',
            'potensi_desas.jumlah_fasilitas_pendidikan',
            'potensi_desas.jumlah_fasilitas_ibadah',
            'potensi_desas.jumlah_tempat_wisata',
            'potensi_desas.jumlah_industri_kecil',
            'potensi_desas.jumlah_bts',
            'potensi_desas.jumlah_operator',
            'potensi_desas.jumlah_kantor_pos',
            'potensi_desas.jumlah_kantor_kurlog',
        )
            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
            ->leftJoin('potensi_desas', 'desas.kode_dagri', '=', 'potensi_desas.kode_dagri')
            ->where('provinsis.nama_provinsi',$provinsi)
            ->where('kabupatens.nama_kabupaten',$kabupaten)
            ->where('kecamatans.nama_kecamatan',$kecamatan)
            ->where('desas.nama_desa',$desa)
            ->first(); 
            // dd($kodeposData);

            $geojson = $kodeposData->geojson;
            $longitude = $kodeposData->longitude;
            $latitude = $kodeposData->latitude;
            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodeposData->kode_new;
            $qrCode = QrCode::format('png')->size(300)->generate($url);
            
            // Convert QR code image to base64
            $base64 = base64_encode($qrCode);

            $kodeposData = [
                'kode_old' => $kodeposData->kode_old,
                'kode_new' => $kodeposData->kode_new,
                'kode_dagri' => $kodeposData->kode_dagri,
                'nama_desa' => ucwords(strtolower($kodeposData->nama_desa)),
                'nama_kecamatan' => ucwords(strtolower($kodeposData->nama_kecamatan)),
                'nama_kabupaten' => ucwords(strtolower($kodeposData->nama_kabupaten)),
                'nama_provinsi' => ucwords(strtolower($kodeposData->nama_provinsi)),
                'jumlah_penduduk' => $kodeposData->jumlah_penduduk,
                'jumlah_fasilitas_pendidikan' => $kodeposData->jumlah_fasilitas_pendidikan,
                'jumlah_fasilitas_ibadah' => $kodeposData->jumlah_fasilitas_ibadah,
                'jumlah_tempat_wisata' => $kodeposData->jumlah_tempat_wisata,
                'jumlah_industri_kecil' => $kodeposData->jumlah_industri_kecil,
                'jumlah_bts' => $kodeposData->jumlah_bts,
                'jumlah_operator' => $kodeposData->jumlah_operator,
                'jumlah_kantor_pos' => $kodeposData->jumlah_kantor_pos,
                'jumlah_kurlog' => $kodeposData->jumlah_kurlog,
            ];

            
            
            if($kodeposData){
                return response()->json([
                    'status' => 'success',
                    'qrcode'=> $base64,
                    'longitude'=>$longitude,
                    'latitude'=>$latitude,
                    'geojson' => $geojson,
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
            
            if ($digitCount === 5) {
                $getkodeposData = KodePos::select(
                    DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),
                    'kode_pos.*',
                    'desas.*',
                    'provinsis.nama_provinsi',
                    'kabupatens.nama_kabupaten',
                    'kecamatans.nama_kecamatan',
                    'potensi_desas.jumlah_penduduk',
                    'potensi_desas.jumlah_fasilitas_pendidikan',
                    'potensi_desas.jumlah_fasilitas_ibadah',
                    'potensi_desas.jumlah_tempat_wisata',
                    'potensi_desas.jumlah_industri_kecil',
                    'potensi_desas.jumlah_bts',
                    'potensi_desas.jumlah_operator',
                    'potensi_desas.jumlah_kantor_pos',
                    'potensi_desas.jumlah_kantor_kurlog',
                )
                    ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                    ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                    ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                    ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                    ->leftJoin('potensi_desas', 'desas.kode_dagri', '=', 'potensi_desas.kode_dagri')
                    ->where('kode_pos.kode_old', $kodepos)
                    ->orderby('kode_pos','desc')
                    ->get();
                    // dd($getkodeposData);
                    $longitude = $getkodeposData->first()->longitude; // Mengambil longitude dari data pertama
                    $latitude = $getkodeposData->last()->latitude; // Mengambil latitude dari data terakhir
                    // $geojsonCollection = collect($getkodeposData)->flatMap(function ($data) {
                    //     $geoJson = json_decode($data->geojson, true);
                    //     return $geoJson;
                    // });
                    
                    // $combinedGeojson = [
                    //     'type' => 'FeatureCollection',
                    //     'features' => $geojsonCollection->all(),
                    // ];
                    
                    // $geojson = json_encode($combinedGeojson);
                    
                   // Menginisialisasi array kosong untuk menyimpan semua fitur GeoJSON
        $features = [];

        // Mengambil hasil query
        $results = $getkodeposData;

        // Iterasi melalui setiap baris hasil query
        foreach ($results as $result) {
            // Mendapatkan GeoJSON dari kolom 'geojson'
            $geoJson = json_decode($result->geojson);

            // Membuat fitur GeoJSON baru dengan properti yang sesuai
            $feature = [
                'type' => 'Feature',
                'geometry' => $geoJson,
                'properties' => [
                    'kode_pos' => $result->kode_new,
                    'kode_dagri' => $result->kode_dagri,
                    'nama_desa' => ucwords(strtolower($result->nama_desa)),
        'nama_kecamatan' => ucwords(strtolower($result->nama_kecamatan)),
        'nama_kabupaten' => ucwords(strtolower($result->nama_kabupaten)),
        'nama_provinsi' => ucwords(strtolower($result->nama_provinsi)),
                ],
            ];

          

            // Menambahkan fitur ke array fitur
            $features[] = $feature;
        }

        // Membuat struktur GeoJSON akhir dengan semua fitur yang digabungkan
        $geojsonResult = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        $geojson = json_encode($geojsonResult);
            
                        if ($getkodeposData->isEmpty()) {
                            $response = [
                                'status' => 'error',
                                'message' => 'Data tidak ditemukan.'
                            ];
                        } else {

                            $data = $getkodeposData->first()->toArray();
                        $desas = $getkodeposData->map(function ($item) {
                            return (object) [
                                'kode_dagri' => $item['kode_dagri'],
                                'kode_mod' => $item['kode_mod'],
                                'kode_new' => $item['kode_new'],
                                'nama_desa' => ucwords(strtolower($item['nama_desa'])),
                                'jumlah_penduduk' => $item['jumlah_penduduk'],
                                'jumlah_fasilitas_pendidikan' => $item['jumlah_fasilitas_pendidikan'],
                                'jumlah_fasilitas_ibadah' => $item['jumlah_fasilitas_ibadah'],
                                'jumlah_tempat_wisata' => $item['jumlah_tempat_wisata'],
                                'jumlah_industri_kecil' => $item['jumlah_industri_kecil'],
                                'jumlah_bts' => $item['jumlah_bts'],
                                'jumlah_operator' => $item['jumlah_operator'],
                                'jumlah_kantor_pos' => $item['jumlah_kantor_pos'],
                                'jumlah_kurlog' => $item['jumlah_kurlog'],
                            ];
                            
                            });


                            // dd($desas);
                    
                            $kodeposData = [
                                'kode_old' => $data['kode_old'],
                                'nama_provinsi' => ucwords(strtolower($data['nama_provinsi'])),
                                'nama_kabupaten' => ucwords(strtolower($data['nama_kabupaten'])),
                                'nama_kecamatan' => ucwords(strtolower($data['nama_kecamatan'])),
                                'desas' => $desas
                            ];
                        
                        }
                

                                
                    $url = 'https://sistemkodeposkominfo.com/index.html#/detail-list/' . $kodeposData['kode_old'];
                    $qrCode = QrCode::format('png')->size(300)->generate($url);

                    // Convert QR code image to base64
                    $base64 = base64_encode($qrCode);
                    }
                    
                    elseif($digitCount === 7){
                        $kodeposData = KodePos::select(
                            DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),
                            'kode_pos.*',
                            'desas.*',
                            'provinsis.nama_provinsi',
                            'kabupatens.nama_kabupaten',
                            'kecamatans.nama_kecamatan',
                            'potensi_desas.jumlah_penduduk',
                            'potensi_desas.jumlah_fasilitas_pendidikan',
                            'potensi_desas.jumlah_fasilitas_ibadah',
                            'potensi_desas.jumlah_tempat_wisata',
                            'potensi_desas.jumlah_industri_kecil',
                            'potensi_desas.jumlah_bts',
                            'potensi_desas.jumlah_operator',
                            'potensi_desas.jumlah_kantor_pos',
                            'potensi_desas.jumlah_kantor_kurlog',
                        )
                            ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                            ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                            ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                            ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                            ->leftJoin('potensi_desas', 'desas.kode_dagri', '=', 'potensi_desas.kode_dagri')
                            ->where('kode_pos.kode_new', $kodepos)
                            ->first();
                            $geojson = $kodeposData->geojson;
                            $longitude = $kodeposData->longitude;
                            $latitude = $kodeposData->latitude;
                            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodeposData->kode_new;
                            $qrCode = QrCode::format('png')->size(300)->generate($url);
                            
                            // Convert QR code image to base64
                            $base64 = base64_encode($qrCode);
                            $kodeposData = [
                                'kode_old' => $kodeposData->kode_old,
                                'kode_new' => $kodeposData->kode_new,
                                'kode_dagri' => $kodeposData->kode_dagri,
                                'nama_desa' => ucwords(strtolower($kodeposData->nama_desa)),
                                'nama_kecamatan' => ucwords(strtolower($kodeposData->nama_kecamatan)),
                                'nama_kabupaten' => ucwords(strtolower($kodeposData->nama_kabupaten)),
                                'nama_provinsi' => ucwords(strtolower($kodeposData->nama_provinsi)),
                                'jumlah_penduduk' => $kodeposData->jumlah_penduduk,
                                'jumlah_fasilitas_pendidikan' => $kodeposData->jumlah_fasilitas_pendidikan,
                                'jumlah_fasilitas_ibadah' => $kodeposData->jumlah_fasilitas_ibadah,
                                'jumlah_tempat_wisata' => $kodeposData->jumlah_tempat_wisata,
                                'jumlah_industri_kecil' => $kodeposData->jumlah_industri_kecil,
                       
                                'jumlah_bts' => $kodeposData->jumlah_bts,
                                'jumlah_operator' => $kodeposData->jumlah_operator,
                                'jumlah_kantor_pos' => $kodeposData->jumlah_kantor_pos,
                                'jumlah_kurlog' => $kodeposData->jumlah_kurlog,
                            ];
                    } elseif($digitCount === 10){
                        $kodeposData = KodePos::select(  DB::raw("ST_AsGeoJSON(desas.geom) AS geojson"),
                        'kode_pos.*',
                        'desas.*',
                        'provinsis.nama_provinsi',
                        'kabupatens.nama_kabupaten',
                        'kecamatans.nama_kecamatan',
                        'potensi_desas.jumlah_penduduk',
                        'potensi_desas.jumlah_fasilitas_pendidikan',
                        'potensi_desas.jumlah_fasilitas_ibadah',
                        'potensi_desas.jumlah_tempat_wisata',
                        'potensi_desas.jumlah_industri_kecil',
                        'potensi_desas.jumlah_bts',
                        'potensi_desas.jumlah_operator',
                        'potensi_desas.jumlah_kantor_pos',
                        'potensi_desas.jumlah_kantor_kurlog',
                    )
                        ->leftJoin('desas', 'kode_pos.kode_dagri', '=', 'desas.kode_dagri')
                        ->leftJoin('kecamatans', 'desas.kode_kec', '=', 'kecamatans.kode_kec')
                        ->leftJoin('kabupatens', 'kecamatans.kode_kab', '=', 'kabupatens.kode_kab')
                        ->leftJoin('provinsis', 'kabupatens.kode_prov', '=', 'provinsis.kode_prov')
                        ->leftJoin('potensi_desas', 'desas.kode_dagri', '=', 'potensi_desas.kode_dagri')
                        ->where('kode_pos.kode_dagri', $kodepos)
                            ->first();
                            $geojson = $kodeposData->geojson;
                            $longitude = $kodeposData->longitude;
                            $latitude = $kodeposData->latitude;
                            $url = 'https://sistemkodeposkominfo.com/index.html#/detail/' . $kodeposData->kode_new;
                            $qrCode = QrCode::format('png')->size(300)->generate($url);
                            // Convert QR code image to base64
                            $base64 = base64_encode($qrCode);
                            $kodeposData = [
                                'kode_old' => $kodeposData->kode_old,
                                'kode_new' => $kodeposData->kode_new,
                                'kode_dagri' => $kodeposData->kode_dagri,
                                'nama_desa' => ucwords(strtolower($kodeposData->nama_desa)),
                                'nama_kecamatan' => ucwords(strtolower($kodeposData->nama_kecamatan)),
                                'nama_kabupaten' => ucwords(strtolower($kodeposData->nama_kabupaten)),
                                'nama_provinsi' => ucwords(strtolower($kodeposData->nama_provinsi)),
                                'jumlah_penduduk' => $kodeposData->jumlah_penduduk,
                                'jumlah_fasilitas_pendidikan' => $kodeposData->jumlah_fasilitas_pendidikan,
                                'jumlah_fasilitas_ibadah' => $kodeposData->jumlah_fasilitas_ibadah,
                                'jumlah_tempat_wisata' => $kodeposData->jumlah_tempat_wisata,
                                'jumlah_industri_kecil' => $kodeposData->jumlah_industri_kecil,
                                'jumlah_bts' => $kodeposData->jumlah_bts,
                                'jumlah_operator' => $kodeposData->jumlah_operator,
                                'jumlah_kantor_pos' => $kodeposData->jumlah_kantor_pos,
                                'jumlah_kurlog' => $kodeposData->jumlah_kurlog,
                            ];
                    } else {
                        return response()->json(['message' => 'Invalid Kodepos'], 400);
                    }
                
                    if($kodeposData){
                        return response()->json([
                            'status' => 'success',
                            'qrcode'=> $base64,
                            'longitude'=>$longitude,
                            'latitude'=>$latitude,
                            'geojson' => $geojson,
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
                    'wilayah' => $item->nama_desa . ', ' . $item->nama_kecamatan . ', ' . $item->nama_kabupaten . ', ' . $item->nama_provinsi
                ];
            });
    
            // Mengubah teks wilayah menjadi Title Case
            $data = $data->map(function ($item) {
                return [
                    'wilayah' => ucwords(strtolower($item['wilayah'])),
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
