<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use app\Http\Controllers\KeranjangController;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KelasController extends Controller
    {
        public function show($id)
        {
            $kelas = Kelas::with('mentor.user:id_user,first_name,last_name,foto_profil')
                ->select('id_kelas', 'id_mentor', 'nama_kelas', 'slug', 'kategori', 'harga', 'thumbnail', 'description', 'status_publikasi')
                ->where('id_kelas', $id)
                ->first();

            if (!$kelas) {
                abort(404, 'Kelas tidak ditemukan');
            }

            $materi = Materi::select('id_materi', 'id_kelas', 'judul_materi', 'urutan')
                ->where('id_kelas', $id)
                ->orderBy('urutan', 'asc')
                ->get()
                ->toArray();

            $kelas = $kelas->toArray();

            return view('detail_kelas', compact('kelas', 'materi'));
        }
    }