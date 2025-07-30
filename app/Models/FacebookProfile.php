<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookProfile extends Model
{
    use UsesUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'facebook_id',
        'firstname',
        'lastname',
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
