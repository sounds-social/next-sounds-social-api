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
    public function index()
    {
        // Sound::where('user_id', Auth::user()->id)->get()
        return SoundsResource::collection(
            Sound::where('is_public', true)
                ->orderBy('id', 'DESC')
                ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSoundRequest $request)
    {
        $request->validated($request->all());

        $sound = Sound::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_public' => $request->is_public,
            'sound_file_path' => '/storage/test.mp3'
        ]);

        return new SoundsResource($sound);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $sound = Sound::where('slug', $slug)->first();

        if (!$sound->is_public) {
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
