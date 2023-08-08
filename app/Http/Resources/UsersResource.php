<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'avatar_file_path' => $this->avatar_file_path,
            'followers_count' => $this->followers_count,
            'follows_count' => $this->follows_count,
            'is_following' => $this->isFollowing(),
            'can_follow' => $this->canFollow()
        ];
    }
}
