<?php

namespace App\Http\Controllers\Api\Detailkelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;

class KelasApiController extends Controller
{
    public function show($id)
    {
        $kelas = Kelas::with('mentor.user:id,name')
            ->select('id_kelas', 'id_mentor', 'nama_kelas', 'slug', 'kategori', 'harga', 'thumbnail', 'description', 'status_publikasi')
            ->where('id_kelas', $id)
            ->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kelas
        ]);
    }

    public function getMateri($id)
    {
        $materi = Materi::select('id_materi', 'id_kelas', 'judul_materi', 'urutan')
            ->where('id_kelas', $id)
            ->orderBy('urutan', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materi
        ]);
    }
}