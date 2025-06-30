<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'name' => new OA\Property(property: 'name', type: 'string', example: 'Name'),
        'email' => new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@test.com'),
        'password' => new OA\Property(property: 'password', type: 'string', example: 'password'),
        'password_confirmation' => new OA\Property(property: 'password_confirmation', type: 'string', example: 'password'),
    ]
)]
class RegistrationRequest extends AbstractApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
