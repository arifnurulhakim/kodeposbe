<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsis'; // Nama tabel di database

    protected $primaryKey = 'id'; // Primary key dari tabel

    public $incrementing = false; // Set false jika primary key bukan tipe auto-increment

    protected $fillable = ['kode_prov', 'nama_provinsi']; // Kolom yang bisa diisi secara massal

    protected $guarded = []; // Kolom yang dikecualikan dari pengisian massal

    public $timestamps = false; // Set true jika tabel memiliki kolom created_at dan updated_at
}
