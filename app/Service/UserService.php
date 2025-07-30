<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Repository\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUsers(object $payload)
    {
        //
    }

    public function findUser(string $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if ($user) {
            return new UserResource($user);
        }

        return response()->json([
            'message' => 'User not found.',
        ], Response::HTTP_BAD_REQUEST);
    }

    public function findCurrentUser()
    {
        $user = Auth::user();

        $user = $this->userRepository->findByUuid($user->uuid);

        return new UserResource($user);
    }

    public function createUser(object $payload)
    {
        $user = $this->userRepository->create($payload);

        return new UserResource($user);
    }

    public function updateUser(object $payload, string $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if (! $user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->update($payload, $uuid);

        return new UserResource($user);
    }

    public function updateUserPassword(object $payload, string $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if (! $user) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! Hash::check($payload->current_password, $user->password)) {
            return response()->json([
                'message' => 'Invalid current password',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->updatePassword($payload, $uuid);

        return new UserResource($user);
    }

    public function deleteUser(string $uuid)
    {
        return $this->userRepository->delete($uuid);
    }
}
