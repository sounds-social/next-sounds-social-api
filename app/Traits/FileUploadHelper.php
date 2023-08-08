<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FileUploadHelper
{
  protected function moveFile($file, $folder)
  {
    $targetPath = public_path() . '/storage/' . $folder . '/';
    $fileName = Str::uuid();

    $moveSuccesful = $file->move(
      $targetPath,
      $fileName
    );

    return ['/file/' . $folder . '/' . $fileName, $moveSuccesful];
  }
}
