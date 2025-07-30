<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function findMany(object $payload, string $sortField, string $sortOrder)
    {
        return User::orderBy($sortField, $sortOrder)
            ->paginate(10);
    }

    public function findByEmail(string $email)
    {
        return User::with([
            'facebook_profile',
        ])
            ->where('email', $email)
            ->first();
    }

    public function findByUuid(string $uuid)
    {
        return User::with([
            'facebook_profile',
        ])
            ->where('uuid', $uuid)
            ->first();
    }

    public function create(object $payload)
    {
        $user = new User;
        $user->firstname = $payload->firstname;
        $user->lastname = $payload->lastname;
        $user->email = $payload->email;
        $user->password = Hash::make($payload->password);
        $user->save();

        return $user->fresh();
    }

    public function update(object $payload, string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $user->firstname = $payload->firstname;
        $user->lastname = $payload->lastname;
        $user->email = $payload->email;
        $user->save();

        return $user->fresh();
    }

    public function updatePassword(object $payload, string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $user->password = Hash::make($payload->password);
        $user->save();

        return $user->fresh();
    }

    public function delete(string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if ($user) {
            $user->delete();

            return response()->json([
                'message' => 'Success.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'No user found.',
        ], Response::HTTP_BAD_REQUEST);
    }
}
