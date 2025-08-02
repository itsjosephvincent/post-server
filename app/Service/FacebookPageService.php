<?php

namespace App\Service;

use App\Http\Resources\FacebookPageResource;
use App\Repository\FacebookPageRepository;
use App\Traits\SortingTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class FacebookPageService
{
    use SortingTraits;

    private $facebookPageRepository;

    public function __construct(FacebookPageRepository $facebookPageRepository)
    {
        $this->facebookPageRepository = $facebookPageRepository;
    }

    public function findPages(object $payload)
    {
        $sortField = $this->sortField($payload, 'id');
        $sortOrder = $this->sortOrder($payload, 'asc');

        $pages = $this->facebookPageRepository->findMany($payload, $sortField, $sortOrder);

        return FacebookPageResource::collection($pages);
    }

    public function getFacebookPagePosts(string $uuid)
    {
        $facebookPage = $this->facebookPageRepository->findByUuid($uuid);

        if (! $facebookPage) {

            return response()->json([
                'message' => 'Facebook page not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $pageId = $facebookPage->page_id;
        $accessToken = $facebookPage->access_token;
        $baseUri = "https://graph.facebook.com/$pageId/posts?limit=10&&access_token=$accessToken";
        $response = Http::get($baseUri);
        $postResponse = json_decode($response);

        return $postResponse;
    }

    public function getNextFacebookPagePosts(object $payload)
    {
        $url = $payload->next_url;
        $response = Http::get($url);
        $postResponse = json_decode($response);

        return $postResponse;
    }

    public function getComments(object $payload)
    {
        $facebookPage = $this->facebookPageRepository->findByUuid($payload->page_uuid);
        $postId = $payload->post_id;
        $accessToken = $facebookPage->access_token;

        $allComments = [];

        $url = "https://graph.facebook.com/$postId/comments?limit=10&access_token=$accessToken";
        $response = Http::get($url);

        $commentResponse = json_decode($response);

        $commenterIds = [];
        foreach ($commentResponse as $data) {
            $commenterIds[] = $data->from->id;
        }

        // while ($url) {
        //     $response = Http::get($url);
        //     $commentResponse = json_decode($response->body());

        //     if (!empty($commentResponse->data)) {
        //         foreach ($commentResponse->data as $data) {
        //             $allComments[] = $data;
        //         }
        //     }
        //     $url = $commentResponse->paging->next ?? null;
        // }

        logger($response);
    }
}
