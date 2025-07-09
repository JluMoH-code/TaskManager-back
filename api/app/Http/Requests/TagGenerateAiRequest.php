<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Openapi\Attributes as OA;

#[OA\Schema(
    properties: [
        'title' => new OA\Property(property: 'title', type: 'string', example: 'title'),
        'description' => new OA\Property(property: 'description', type: 'string', example: 'description', nullable: true),
    ]
)]
class TagGenerateAiRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:6'],
        ];
    }
}
