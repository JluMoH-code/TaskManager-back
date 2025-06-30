<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{

    
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(["message" => "success"]);
        }

        return response()->withErrors([
            'email' => 'Предоставленные учетные данные не соответствуют нашим записям.',
        ])->onlyInput('email');
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
