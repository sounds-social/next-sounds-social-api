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

    if (!$moveSuccesful) {
      return $this->error('File uploaded failed.', 500);
    }

    return ['/file/audio/' . $fileName, $moveSuccesful];
  }
}
