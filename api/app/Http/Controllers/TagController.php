<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Services\TagService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TagController extends Controller
{
    public function __construct(private TagService $tagService)
    {}

    #[OA\Get(
        path: '/api/tags',
        summary: 'Показ тегов авторизованного пользователя',
        tags: ['profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список тегов',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/TagResource')
                )
            ),
        ]
    )]
    public function getTags(Request $request) 
    {
        $tags = $this->tagService->getTagsForUser($request->user());
        return response()->json(TagResource::collection($tags));
    }
}
