<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Home\HomeApiController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Panggil controller API langsung (internal call)
        $apiController = new HomeApiController();
        
        $kelasResponse = $apiController->getKelas();
        $mentorsResponse = $apiController->getMentors();
        $reviewsResponse = $apiController->getReviews();

        // Extract data dari response
        $kelas = $kelasResponse->getData()->data ?? [];
        $mentors = $mentorsResponse->getData()->data ?? [];
        $reviews = $reviewsResponse->getData()->data ?? [];

    }
}