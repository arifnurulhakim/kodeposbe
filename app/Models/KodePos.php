<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    protected $table = 'kode_pos'; // Nama tabel di database

    protected $primaryKey = 'id'; // Primary key dari tabel

    public $incrementing = false; // Set false jika primary key bukan tipe auto-increment

    protected $fillable = ['kode_dagri', 'kode_old', 'kode_mod', 'kode_new']; // Kolom yang bisa diisi secara massal

    protected $guarded = []; // Kolom yang dikecualikan dari pengisian massal

    public $timestamps = true; // Set true jika tabel memiliki kolom created_at dan updated_at

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'kode_desa', 'kode_dagri');
    }


}
