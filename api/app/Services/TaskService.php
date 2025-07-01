<?php

namespace App\Services;

use App\Http\Requests\TaskCreateRequest;
use App\Models\User;

class TaskService
{
    public function createForUser(TaskCreateRequest $request, User $user)
    {
        $task = $user->tasks()->create($request->all());
        return $task;
    }
}