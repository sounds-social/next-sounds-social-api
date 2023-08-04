<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use HttpResponses;

    function upload(Request $request) {
        $file = $request->file('file');

        if ($file->move('uploads', rand() . $file->getClientOriginalName())) {
            return $this->success([
                'success' => true
            ], 'File uploaded successfully.');
        } else {
            return $this->error('File uploaded failed.', 500);
        }
    }
}
