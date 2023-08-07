<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoundRequest;
use App\Http\Resources\SoundsResource;
use App\Models\Sound;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SoundsController extends Controller
{
    use HttpResponses;

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

        return SoundsResource::collection(
            $sounds->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSoundRequest $request)
    {

        $request->validated($request->all());

        $file = $request->file('file');

        $targetPath = public_path() . '/storage/audio/';
        $fileName = Str::uuid();

        $moveSuccesful = $file->move(
            $targetPath, 
            $fileName
        );

        if (!$moveSuccesful) {
            return $this->error('File uploaded failed.', 500);
        }

        $sound = Sound::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_public' => 'true' === $request->is_public,
            'sound_file_path' => '/file/audio/' . $fileName
        ]);

        return new SoundsResource($sound);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $sound = Sound::where('slug', $slug)->first();

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
