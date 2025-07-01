<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {}

    #[OA\Get(
        path: '/api/tasks',
        summary: 'Показ задач авторизованного пользователя',
        tags: ['profile'],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'Список задач',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/TaskResource')
                )
            ),
        ]
    )]
    public function getTasks(Request $request) 
    {
        $tasks = $request->user()->tasks()->get();
        return response()->json(TaskResource::collection($tasks));
    }

    #[OA\Post(
        path: '/api/task',
        summary: 'Создание задачи для авторизованного пользователя',
        tags: ['profile'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskCreateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: 'Созданная задача',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource'),
            ),
        ]
    )]
    public function createTask(TaskCreateRequest $request)
    {
        $task = $this->taskService->createForUser($request, $request->user());
        return response()->json(new TaskResource($task));  
    }
}
