<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodePembayaran extends Model
{
    use HasFactory;
    protected $table = 'metode_pembayaran';
    protected $primaryKey = 'id_mp';
    protected $fillable = ['nama_metode', 'nomor_rekening', 'nama_pemilik', 'is_active'];
}
