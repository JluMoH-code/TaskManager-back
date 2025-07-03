<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskFilterRequest;
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
        parameters: [
            new OA\Parameter(
                name: 'title',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: 'title', nullable: true)
            ),
            new OA\Parameter(
                name: 'priority',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: 'low,high', nullable: true)
            ),
            new OA\Parameter(
                name: 'tags',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: 'tag1,tag2,tag3', nullable: true)
            ),
            new OA\Parameter(
                name: 'deadline_from',
                in: 'query',
                schema: new OA\Schema(type: 'string', format: 'date-time', example: '2025-01-01 21:00:00', nullable: true)
            ),
            new OA\Parameter(
                name: 'deadline_to',
                in: 'query',
                schema: new OA\Schema(type: 'string', format: 'date-time', example: '2026-01-01 21:00:00', nullable: true)
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['title', 'deadline', 'priority'], example: 'title', nullable: true)
            ),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'], example: 'asc', nullable: true)
            ),
            new OA\Parameter(
                name: 'active_only',
                in: 'query',
                schema: new OA\Schema(type: 'boolean', example: false, nullable: true)
            ),
        ],
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
    public function getTasks(TaskFilterRequest $request)
    {
        $tasks = $this->taskService->getTasksByUser($request->user(), $request);
        return response()->json(TaskResource::collection($tasks));
    }

    #[OA\Post(
        path: '/api/task',
        summary: 'Создание задачи для авторизованного пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskCreateRequest')
        ),
        tags: ['profile'],
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

    #[OA\Put(
        path: '/api/task/{id}',
        summary: 'Редактирование задачи авторизованного пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskCreateRequest')
        ),
        tags: ['profile'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Отредактированная задача',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource'),
            ),
        ]
    )]
    public function updateTask(int $id, TaskCreateRequest $request)
    {
        $task = $this->taskService->updateTask($id, $request, $request->user());
        return response()->json(new TaskResource($task));
    }

    #[OA\Patch(
        path: '/api/task/{id}/toggle',
        summary: 'Редактирование задачи авторизованного пользователя',
        tags: ['profile'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID задачи',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Созданная задача',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource'),
            ),
        ]
    )]
    public function toggleActiveTask(int $id, Request $request)
    {
        $task = $this->taskService->toggleActiveTask($id, $request->user());
        return response()->json(new TaskResource($task));
    }
}
