<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Service\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        //
    }

    public function store(UserStoreRequest $request)
    {
        return $this->userService->createUser($request);
    }

    public function user()
    {
        return $this->userService->findCurrentUser();
    }

    public function show(string $uuid)
    {
        return $this->userService->findUser($uuid);
    }

    public function update(UserUpdateRequest $request, string $uuid)
    {
        return $this->userService->updateUser($request, $uuid);
    }

    public function updatePassword(UserUpdatePasswordRequest $request, string $uuid)
    {
        return $this->userService->updateUserPassword($request, $uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->userService->deleteUser($uuid);
    }
}
