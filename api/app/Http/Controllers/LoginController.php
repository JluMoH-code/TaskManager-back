<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{

    #[OA\Post(
        path: '/login',
        summary: 'Вход',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/LoginRequest'))]
        ),
        tags: ['auth'],
        responses: [
            new OA\Response(response: 204, description: 'No content'),
            new OA\Response(response: 422, description: 'Unprocessable Content')
        ]
    )]
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->all();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->noContent(204);
        }

        return response()->json(['message' => 'Некорректные данные']);
    }

    #[OA\Get(
        path: '/logout',
        summary: 'Выход',
        tags: ['auth'],
        responses: [
            new OA\Response(response: 204, description: 'No content'),
            new OA\Response(response: 422, description: 'Unprocessable Content')
        ]
    )]
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent(204);
    }

    #[OA\Post(
        path: '/register',
        summary: 'Регистрация',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/RegistrationRequest'))]
        ),
        tags: ['auth'],
        responses: [
            new OA\Response(response: 204, description: 'No content'),
            new OA\Response(response: 422, description: 'Unprocessable Content')
        ]
    )]
    public function store(RegistrationRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->noContent(204);
    }

    #[OA\Get(
        path: '/sanctum/csrf-cookie',
        summary: 'Метод для записи csrf куки в браузер',
        tags: ['csrf'],
        responses: [new OA\Response(response: 204, description: 'No content')]
    )]
    public function csrfCookie(): void
    {}
}
