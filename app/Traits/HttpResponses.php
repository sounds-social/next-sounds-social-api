<?php

namespace App\Traits;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

trait HttpResponses
{
  protected function success($data, $message = null, $code = 200)
  {
    return response()->json([
      'status' => 'Request was successful.',
      'message' => $message,
      'data' => $data
    ], $code);
  }

  protected function error($message, $code)
  {
    return response()->json([
      'status' => 'Error has occured.',
      'message' => $message,
    ], $code);
  }

  private function isNotAuthorized(string $userId, callable $callback = null)
  {
    if ($userId !== (string) Auth::user()->id) {
      return $this->error(
        '',
        'You do not have permission.',
        403
      );
    } elseif ($callback) {
      return $callback();
    }
  }
}
