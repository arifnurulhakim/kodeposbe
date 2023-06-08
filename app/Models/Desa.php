<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desas'; // Nama tabel di database

    protected $primaryKey = 'id'; // Primary key dari tabel

    public $incrementing = false; // Set false jika primary key bukan tipe auto-increment

    protected $fillable = ['kode_desa', 'kode_kec', 'kode_kab', 'kode_prov', 'nama_desa']; // Kolom yang bisa diisi secara massal

    protected $guarded = []; // Kolom yang dikecualikan dari pengisian massal

    public $timestamps = false; // Set true jika tabel memiliki kolom created_at dan updated_at

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kode_kec', 'kode_kec')
            ->whereColumn('kode_kab', 'kode_kab')
            ->whereColumn('kode_prov', 'kode_prov');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kode_kab', 'kode_kab')->whereColumn('kode_prov', 'kode_prov');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provisi::class, 'kode_prov', 'kode_prov');
    }
}
