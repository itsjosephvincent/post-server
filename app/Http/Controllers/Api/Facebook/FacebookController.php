<?php

namespace App\Http\Controllers\Api\Facebook;

use App\Http\Controllers\Controller;
use App\Service\FacebookService;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    private $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function index(Request $request)
    {
        return $this->facebookService->token($request);
    }
}
