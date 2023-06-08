<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman'; // Nama tabel di database

    protected $fillable = [
        'alamat_penerima',
        'alamat_pengirim',
        'status_kiriman',
        'info_kiriman',
        'notes_kiriman',
        'payment',
    ];

    // Jika tabel memiliki kolom created_at dan updated_at
    public $timestamps = true;

    // Jika primary key bukan tipe auto-increment
    protected $primaryKey = 'id';

    // Jika ada kolom yang dikecualikan dari pengisian massal
    protected $guarded = [];
}
