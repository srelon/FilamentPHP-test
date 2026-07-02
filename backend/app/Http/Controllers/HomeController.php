<?php

namespace App\Http\Controllers;

use App\Services\HomeService;

class HomeController extends Controller
{
    public function __construct(protected HomeService $homeService)
    {
    }

    public function index()
    {
        return $this->respondWithJson($this->homeService->getHome());
    }
}
