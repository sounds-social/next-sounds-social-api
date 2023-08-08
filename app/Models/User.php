<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Sound;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'slug'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Sound::class, 'likes');
    }

    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follow_id');
    }
    
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follow_id', 'user_id');
    }

    public static function follow ($userId, $followId) {
        return Follow::create([
            'user_id' => $userId,
            'follow_id' => $followId,
        ]);
    }

    public static function unfollow ($userId, $followId) {
        return Follow::where([
            'user_id' => $userId,
            'follow_id' => $followId,
        ])->delete();
    }

    public function isFollowing () {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return Follow::where([
            'user_id' => $user->id,
            'follow_id' => $this->id,
        ])->exists();
    }

    public function canFollow () {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $this->id !== $user->id;
    }
}
