<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\FileUploadHelper;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    use HttpResponses;
    use FileUploadHelper;

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $user = User::where('slug', $slug)
            ->withCount(['followers', 'follows', 'likes'])
            ->first();

        return new UsersResource($user);
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateUserRequest $request, string $slug)
    {
        $request->validated($request->all());

        $user = User::where('slug', $slug)->firstOrFail();
        $authUser = Auth::user();

        if (empty($authUser) || $authUser->id !== $user->id) {
            return $this->error('Unauthorized', 401);
        }

        $file = $request->file('avatar_file');

        if ($file) {
            list($filePath, $moveSuccesful) = $this->moveFile($file, 'avatar');

            if (!$moveSuccesful) {
                return $this->error('Avatar file uploaded failed.', 500);
            }

            $user->avatar_file_path = $filePath;
        }

        $user->name = $request->name;

        $user->save();

        return new UsersResource($user);
    }
}
