<?php

namespace App\Repository;

use App\Models\FacebookProfile;
use Illuminate\Support\Facades\Auth;

class FacebookProfileRepository
{
    public function create(object $payload)
    {
        $user = Auth::user();

        $profile = new FacebookProfile;
        $profile->user_id = $user->id;
        $profile->facebook_id = $payload->facebook_id;
        $profile->firstname = $payload->firstname;
        $profile->lastname = $payload->lastname;
        $profile->save();

        return $profile->fresh();
    }
}
