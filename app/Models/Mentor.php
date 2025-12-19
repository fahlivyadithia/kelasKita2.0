<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mentor extends Model
{
    use HasFactory;

    protected $table = 'mentors';
    protected $primaryKey = 'id_mentor';

    protected $fillable = [
        'id_user',
        'keahlian',
        'deskripsi_mentor',
        'status',
        'bank_penerima',
        'nomor_rekening_mentor',
        'nama_rekening_mentor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_mentor');
    }
}
