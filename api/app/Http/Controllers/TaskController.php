<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    
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
}
