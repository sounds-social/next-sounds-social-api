<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'sound_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function sound(): BelongsTo
  {
    return $this->belongsTo(Sound::class, 'sound_id');
  }

  public static function like($userId, $soundId)
  {
    return Like::create([
      'user_id' => $userId,
      'sound_id' => $soundId,
    ]);
  }

  public static function removeLike($userId, $soundId)
  {
    return Like::where([
      'user_id' => $userId,
      'sound_id' => $soundId,
    ])->delete();
  }
}
