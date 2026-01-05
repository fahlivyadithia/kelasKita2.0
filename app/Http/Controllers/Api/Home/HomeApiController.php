<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mentor;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    public function getKelas()
    {
        $kelas = Kelas::with('mentor.user') // Ambil mentor beserta user-nya
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kelas
        ]);
    }

    public function getMentors()
    {
        //  Sesuaikan dengan kolom yang ada di tabel users
        $mentors = Mentor::with('user:id_user,first_name,last_name,foto_profil') 
            ->select('id_mentor', 'id_user', 'keahlian', 'deskripsi_mentor')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $mentors
        ]);
    }

    public function getReviews()
    {
        // suaikan dengan kolom yang ada
        $reviews = Review::with([
                'user:id_user,first_name,last_name,foto_profil',  // Kolom yang benar
                'kelas:id_kelas,nama_kelas'
            ])
            ->where('bintang', '>=', 4)
            ->select('id_review', 'id_user', 'id_kelas', 'bintang', 'isi_review', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}