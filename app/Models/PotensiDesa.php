<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotensiDesa extends Model
{
    protected $table = 'potensi_desas';

    protected $fillable = [
        'kode_dagri',
        'jumlah_penduduk',
        'jumlah_fasilitas_pendidikan',
        'jumlah_fasilitas_ibadah',
        'jumlah_tempat_wisata',
        'jumlah_industri_kecil',
    ];

    // Additional model logic or relationships can be defined here
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'kode_dagri', 'kode_dagri');
    }
}
