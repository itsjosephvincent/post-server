<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UsesUuid;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    public function facebook_profile(): HasOne
    {
        return $this->hasOne(FacebookProfile::class);
    }
}
