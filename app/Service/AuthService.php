<?php

namespace App\Service;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Repository\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(object $payload)
    {
        $user = $this->userRepository->findByEmail($payload->email);

        if (! $user) {
            return response()->json([
                'message' => 'No account found with the email address provided.',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! Hash::check($payload->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = (object) [
            'token' => $user->createToken('auth-token')->plainTextToken,
            'user' => new UserResource($user),
        ];

        return new AuthResource($data);
    }

    public function logout(object $payload)
    {
        $payload->user()->tokens()->delete();

        return response()->json([
            'message' => 'Success.',
        ], Response::HTTP_OK);
    }
}
