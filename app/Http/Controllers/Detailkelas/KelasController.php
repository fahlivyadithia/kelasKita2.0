<?php

namespace App\Http\Controllers\Detailkelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KelasController extends Controller
{
    public function show($id)
    {
        $apiBaseUrl = config('app.url') . '/api';

        $kelasResponse = Http::get("{$apiBaseUrl}/kelas/{$id}");
        $materiResponse = Http::get("{$apiBaseUrl}/kelas/{$id}/materi");

        if (!$kelasResponse->successful()) {
            abort(404, 'Kelas tidak ditemukan');
        }

        $kelas = $kelasResponse->json()['data'];
        $materi = $materiResponse->successful() ? $materiResponse->json()['data'] : [];

        return view('detail_kelas', compact('kelas', 'materi'));
    }
}