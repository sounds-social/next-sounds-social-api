<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoundsResource extends JsonResource
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
            'created_at' => $this->created_at,
            'is_public' => (boolean) $this->is_public,
            'sound_file_path' => $this->sound_file_path,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'user' => User::find($this->user_id)
        ];
        
        return parent::toArray($request);
    }
}
