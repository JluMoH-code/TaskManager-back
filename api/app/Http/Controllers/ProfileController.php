<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Log;
use OpenApi\Attributes as OA;

class ProfileController extends Controller
{
    
    #[OA\Get(
        path: '/api/profile',
        summary: 'Показ информации о текущем авторизованном пользователе',
        tags: ['profile'],
        responses: [
            new OA\Response(response: 200, ref: '#/components/schemas/ProfileResource'),
        ]
    )]
    public function show(Request $request) 
    {
        $user = $request->user();
        return response()->json(new ProfileResource($user), 204);
    }
}
