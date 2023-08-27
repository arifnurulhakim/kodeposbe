<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'user_id'];

    // Relasi dengan pengguna (opsional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
