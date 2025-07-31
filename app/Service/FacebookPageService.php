<?php

namespace App\Service;

use App\Http\Resources\FacebookPageResource;
use App\Repository\FacebookPageRepository;
use App\Traits\SortingTraits;

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
}
