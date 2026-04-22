<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'username'   => $this->username,
            'bio'        => $this->bio,
            'avatar_url' => $this->avatar_url
                ? asset('storage/' . $this->avatar_url)
                : null,
        ];
    }
}