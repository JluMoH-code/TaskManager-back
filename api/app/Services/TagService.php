<?php

namespace App\Services;

use App\Models\User;

class TagService 
{
    public function getTagsForUser(User $user)
    {
        $tags = $user->tags()->orderBy('id')->get();
        return $tags;
    }
}