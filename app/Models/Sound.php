<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Sound extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'is_public',
        'sound_file_path',
        'cover_file_path'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function hasLiked()
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return Like::where([
            'user_id' => $user->id,
            'sound_id' => $this->id,
        ])->exists();
    }

    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class);
    }
}
