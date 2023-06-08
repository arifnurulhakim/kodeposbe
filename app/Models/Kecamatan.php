<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatans'; // Nama tabel di database

    protected $primaryKey = 'id'; // Primary key dari tabel

    public $incrementing = false; // Set false jika primary key bukan tipe auto-increment

    protected $fillable = ['kode_kec', 'kode_kab', 'kode_prov', 'nama_kecamatan']; // Kolom yang bisa diisi secara massal

    protected $guarded = []; // Kolom yang dikecualikan dari pengisian massal

    public $timestamps = false; // Set true jika tabel memiliki kolom created_at dan updated_at

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kode_kab', 'kode_kab')->whereColumn('kode_prov', 'kode_prov');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provisi::class, 'kode_prov', 'kode_prov');
    }
}
