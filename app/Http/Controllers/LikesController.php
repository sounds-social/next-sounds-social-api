<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Models\Like;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user_id;

        if (!$userId) {
            return $this->error('User ID is required.', 400);
        }

        $limit = $request->limit;

        $likes = Like::where('user_id', $userId)
            ->with(['sound', 'user'])
            ->orderBy('id', 'desc');

        if ($limit) {
            $likes->take($limit);
        }

        return $likes->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request)
    {
        $data = $request->validated();

        if ($data['method'] === 'like') {
            $like = Like::like($data['user_id'], $data['sound_id']);
        } else if ($data['method'] === 'removeLike') {
            Like::removeLike($data['user_id'], $data['sound_id']);

            return response()->json(null);
        }

        return response()->json($like);
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLikeRequest $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }
}
