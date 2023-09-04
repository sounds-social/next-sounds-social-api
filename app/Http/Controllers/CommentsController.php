<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentsResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $comments = Comment::with(['user', 'sound'])
            ->orderBy('id', 'desc');

        if ($request->sound_id) {
            $comments->where('sound_id', $request->sound_id);
        }

        // TODO: for sound_id
        return CommentsResource::collection(
            $comments->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $request->validated($request->all());

        $comment = Comment::create($request->all());

        return new CommentsResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $user = Auth::user();

        if ($user->id === $comment->user()->first()->id) {
            $comment->delete();

            return new JsonResponse(null, 204);
        }
    
        return new JsonResponse(['message' => 'Unauthorized'], 401);
    }
}
