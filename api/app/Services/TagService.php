<?php

namespace App\Services;

use App\Http\Requests\TagSearchRequest;
use App\Models\User;

class TagService 
{
    public function getTagsForUser(TagSearchRequest $request, User $user)
    {
        $query = $user->tags();

        if ($request->filled('search')) {
            $query->where('title', 'ilike', $request->search .'%');
        }

        $tags = $query->orderBy('id')->get();
        return $tags;
    }
}