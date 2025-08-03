<?php

namespace App\Http\Controllers\Api\FacebookPage;

use App\Http\Controllers\Controller;
use App\Service\FacebookPageService;
use Illuminate\Http\Request;

class FacebookPageController extends Controller
{
    private $facebookPageService;

    public function __construct(FacebookPageService $facebookPageService)
    {
        $this->facebookPageService = $facebookPageService;
    }

    public function index(Request $request)
    {
        return $this->facebookPageService->findPages($request);
    }

    public function show(string $uuid)
    {
        return $this->facebookPageService->getFacebookPagePosts($uuid);
    }

    public function nextPost(Request $request)
    {
        return $this->facebookPageService->getNextFacebookPagePosts($request);
    }

    public function postInsights(Request $request)
    {
        return $this->facebookPageService->getReactInsights($request);
    }
}
