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
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'avatar_file_path' => $this->avatar_file_path,            
            'is_following' => $this->isFollowing(),
            'can_follow' => $this->canFollow()
        ];

        if (isset($this->followers_count)) {
            $data['followers_count'] = $this->followers_count;
        }

        if (isset($this->follows_count)) {
            $data['follows_count'] = $this->follows_count;
        }

        return $data;
    }
}
