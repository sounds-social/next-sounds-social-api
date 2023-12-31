<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoundRequest;
use App\Http\Requests\UpdateSoundRequest;
use App\Http\Resources\SoundsResource;
use App\Models\Sound;
use App\Models\User;
use App\Traits\FileUploadHelper;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SoundsController extends Controller
{
    use HttpResponses;
    use FileUploadHelper;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user_id;

        $currentUser = Auth::user();

        $sounds = Sound::orderBy('id', 'DESC');

        if (!$currentUser || $currentUser->id != $userId) {
            $sounds->where('is_public', true);
        }

        if ($userId) {
            $sounds->where('user_id', $userId);
        }

        if ($request->following && $currentUser) {
            $followIds = $currentUser->follows()
                ->pluck('users.id')
                ->toArray();

            $sounds->whereIn('user_id', $followIds);
        }

        $likedBy = $request->liked_by;

        if ($likedBy) {
            $user = User::where('id', $likedBy)->first();

            $likes = $user->likes()
                ->orderBy('likes.id', 'DESC')
                ->pluck('likes.sound_id')->toArray();

            $sounds->whereIn('id', $likes);
        }

        $sounds->with('user');

        if ($request->limit || $request->offset) {
            $sounds
                ->skip($request->offset)
                ->take($request->limit);
        } else {
            $sounds->limit(15);
        }

        if ($request->count) {
            return $this->success([
                'count' => $sounds->count()
            ], 'Sounds count retrieved successfully');
        }

        return SoundsResource::collection($sounds->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSoundRequest $request)
    {
        $request->validated($request->all());

        $file = $request->file('file');

        list($filePath, $moveSuccesful) = $this->moveFile($file, 'audio');

        if (!$moveSuccesful) {
            return $this->error('Sound file uploaded failed.', 500);
        }

        $coverFile = $request->file('cover_file');
        $coverFilePath = null;

        if ($coverFile) {
            list($coverFilePath, $moveSuccesful) = $this->moveFile(
                $coverFile,
                'covers'
            );

            if (!$moveSuccesful) {
                return $this->error('Cover file uploaded failed.', 500);
            }
        }

        $slug = Str::slug($request->title);
        $soundWithSlug = Sound::where('slug', $slug)->first();

        if ($soundWithSlug) {
            $slug = $slug . '-' . time();
        }

        $sound = Sound::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'is_public' => 'true' === $request->is_public,
            'sound_file_path' => $filePath,
            'cover_file_path' => $coverFilePath
        ]);

        return new SoundsResource($sound);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $sound = Sound::where('slug', $slug)
            ->withCount(['likes'])
            ->with(['user', 'comments'])
            ->first();

        if (!$sound->is_public && Auth::user()->id !== $sound->user_id) {
            return $this->error(
                'Sound not found',
                404
            );
        }

        return new SoundsResource($sound);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoundRequest $request, string $slug)
    {
        $request->validated($request->all());

        $sound = Sound::where('slug', '=', $slug)->firstOrFail();

        if (!$sound) {
            return $this->error(
                'Sound not found',
                404
            );
        }

        $coverFile = $request->file('cover_file');
        $coverFilePath = null;

        if ($coverFile) {
            list($coverFilePath, $moveSuccesful) = $this->moveFile(
                $coverFile,
                'covers'
            );

            if (!$moveSuccesful) {
                return $this->error('Cover file uploaded failed.', 500);
            }

            $sound->cover_file_path = $coverFilePath;
        }

        $sound->title = $request->title;
        $sound->description = $request->description;
        $sound->is_public = 'true' === $request->is_public;

        $sound->save();

        return new SoundsResource($sound);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
