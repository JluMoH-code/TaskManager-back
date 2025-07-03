<?php

namespace App\Services;

use App\Enums\PriorityTaskEnum;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Models\User;

class TaskService
{

    public function getTasksByUser(User $user, TaskFilterRequest $request)
    {
        $query = $user->tasks();

        if ($request->filled('title')) {
            $query->where('title', 'ilike', '%'. $request->title .'%');
        }

        if ($request->filled('priority')) {
            $priorities = array_filter($request->priority);
            if (!empty($priorities)) {
                $query->whereIn('priority', $priorities);
            }
        }

        if ($request->filled('tags')) {
            $tags = array_filter($request->tags);
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $query->whereHas('tags', function ($query) use ($tag) {
                        $query->where('tags.title', $tag);
                    });
                }
            }
        }

        if ($request->filled('deadline_from')) {
            $query->where('deadline', '>=', $request->deadline_from);
        }

        if ($request->filled('deadline_to')) {
            $query->where('deadline', '<=', $request->deadline_to);
        }

        if ($request->filled('active_only') && $request->active_only) {
            $query->where('active', '=', true);
        }

        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';

        if ($sortBy === 'priority') {
            $orderByCase = "CASE priority ";
            foreach (PriorityTaskEnum::sortWeights() as $value => $weight) {
                $orderByCase .= "WHEN '{$value}' THEN {$weight} ";
            }
            $orderByCase .= "ELSE 0 END";

            $orderRaw = $orderByCase . " " . $sortOrder;

            $query->orderByRaw($orderRaw);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->get();
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