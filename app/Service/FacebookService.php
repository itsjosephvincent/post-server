<?php

namespace App\Service;

use App\Http\Resources\FacebookPageResource;
use App\Http\Resources\FacebookProfileResource;
use App\Repository\FacebookPageRepository;
use App\Repository\FacebookProfileRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class FacebookService
{
    private $facebookProfileRepository;

    private $facebookPageRepository;

    public function __construct(
        FacebookProfileRepository $facebookProfileRepository,
        FacebookPageRepository $facebookPageRepository
    ) {
        $this->facebookProfileRepository = $facebookProfileRepository;
        $this->facebookPageRepository = $facebookPageRepository;
    }

    public function token(object $payload)
    {
        try {
            DB::beginTransaction();
            $tokenUri = 'https://graph.facebook.com/v23.0/oauth/access_token';

            $params = [
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'redirect_uri' => env('FACEBOOK_LOGIN_REDIRECT'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                'scope' => 'public_profile,read_insights,pages_show_list,pages_read_engagement,pages_read_user_content,pages_manage_posts,pages_manage_engagement',
                'code' => $payload->code,
            ];

            $response = Http::get($tokenUri, $params);
            $response = $response->json();
            logger($response);
            $accessToken = $response['access_token'];
            $appSecret = env('FACEBOOK_CLIENT_SECRET');
            $appSecretProof = hash_hmac('sha256', $accessToken, $appSecret);
            $profileUri = 'https://graph.facebook.com/v23.0/me';

            $profileParams = [
                'access_token' => $accessToken,
                'appsecret_proof' => $appSecretProof,
                'fields' => 'id,first_name,last_name,email',
            ];

            $profileResponse = Http::get($profileUri, $profileParams);
            $profile = json_decode($profileResponse);

            $facebookProfilePayload = (object) [
                'facebook_id' => $profile->id,
                'firstname' => $profile->first_name,
                'lastname' => $profile->last_name,
            ];

            $facebookProfile = $this->facebookProfileRepository->create($facebookProfilePayload);

            $this->saveFacebookPages($profile->id, $accessToken);
            DB::commit();

            return new FacebookProfileResource($facebookProfile);
        } catch (Throwable $e) {
            DB::rollBack();

            logger('Error in connecting facebook: '.$e);

            return response()->json([
                'message' => 'Something went wrong while connecting to Facebook. Please try again shortly. If the problem continues, please reach out to your system administrator.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function saveFacebookPages($profileId, $accessToken)
    {
        $response = Http::get('https://graph.facebook.com/v23.0/oauth/access_token?grant_type=fb_exchange_token&client_id='.env('FACEBOOK_CLIENT_ID').'&client_secret='.env('FACEBOOK_CLIENT_SECRET').'&fb_exchange_token='.$accessToken);
        $response = $response->json();

        $accessToken = $response['access_token'];

        $pageResponse = Http::get('https://graph.facebook.com/v23.0/'.$profileId.'/accounts?fields=name,access_token,id&access_token='.$accessToken);

        $pages = json_decode($pageResponse->getBody(), true);

        foreach ($pages['data'] as $page) {
            $facebookPagePayload = (object) [
                'page_id' => $page['id'],
                'name' => $page['name'],
                'access_token' => $page['access_token'],
            ];

            $facebookPages[] = $this->facebookPageRepository->create($facebookPagePayload);
        }

        return FacebookPageResource::collection($facebookPages);
    }
}
