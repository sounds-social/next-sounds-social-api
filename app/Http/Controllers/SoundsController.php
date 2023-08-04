<?php

namespace App\Http\Controllers;

use App\Http\Resources\SoundsResource;
use App\Models\Sound;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sound $sound)
    {
        if (!$sound->isPublic) {
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
