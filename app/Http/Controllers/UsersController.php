<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\HttpResponses;

class UsersController extends Controller
{
    use HttpResponses;

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $user = User::where('slug', $slug)
            ->withCount(['followers', 'follows'])
            ->first();

        return new UsersResource($user);
    }
}
