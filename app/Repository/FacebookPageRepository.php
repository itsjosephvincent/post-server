<?php

namespace App\Repository;

use App\Models\FacebookPage;
use Illuminate\Support\Facades\Auth;

class FacebookPageRepository
{
    public function findMany(object $payload, string $sortField, string $sortOrder)
    {
        $user = Auth::user();

        return FacebookPage::where('user_id', $user->id)
            ->filter($payload->all())
            ->orderBy($sortField, $sortOrder)
            ->paginate(10);
    }

    public function create(object $payload)
    {
        $user = Auth::user();

        $page = new FacebookPage;
        $page->user_id = $user->id;
        $page->page_id = $payload->page_id;
        $page->name = $payload->name;
        $page->access_token = $payload->access_token;
        $page->save();

        return $page->fresh();
    }
}
