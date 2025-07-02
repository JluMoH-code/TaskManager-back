<?php

namespace App\Http\Requests;

use App\Enums\PriorityTaskEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Openapi\Attributes as OA;

#[OA\Schema(
    properties: [
        'title' => new OA\Property(property: 'title', type: 'string', example: 'title'),
        'description' => new OA\Property(property: 'description', type: 'string', example: 'description'),
        'deadline' => new OA\Property(property: 'deadline', type: 'datetime', example: '2025-01-01 21:00:00'),
        'priority' => new OA\Property(property: 'priority' ,ref: '#/components/schemas/PriorityTaskEnum'),
    ]
)]
class TaskCreateRequest extends FormRequest
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
            'description' => ['string', 'min:6'],
            'deadline' => ['string', 'date', 'after_or_equal:today'],
            'priority' => ['string', Rule::in(PriorityTaskEnum::values())],
        ];
    }
}
