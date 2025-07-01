<?php

namespace App\Services;

use App\Http\Requests\TaskCreateRequest;
use App\Models\User;

class TaskService
{

    public function getTasksByUser(User $user) 
    {
        return $user->tasks;
    }

    public function createForUser(TaskCreateRequest $request, User $user)
    {
        $task = $user->tasks()->create($request->all());
        return $task;
    }

    public function updateTask(int $id, TaskCreateRequest $request, User $user)
    {
        $task = $user->tasks()->findOrFail($id);
        $task->update($request->all());
        return $task;
    }

    public function toggleActiveTask(int $id, User $user)
    {
        $task = $user->tasks()->findOrFail($id);
        $task->active = !$task->active;
        $task->save();
        return $task;
    }
}