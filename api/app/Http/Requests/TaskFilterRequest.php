<?php

namespace App\Http\Requests;

use App\Enums\PriorityTaskEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'title' => new OA\Property(property: 'title', type: 'string', example: 'title', nullable: true),
        'priority' => new OA\Property(property: 'priority', type: 'string', example: 'low,high', nullable: true),
        'tags' => new OA\Property(property: 'tags', type: 'string', example: 'tag', nullable: true),
        'deadline_from' => new OA\Property(property: 'deadline_from', type: 'date', example: '2025-01-01 21:00:00', nullable: true),
        'deadline_to' => new OA\Property(property: 'deadline_to', type: 'date', example: '2026-01-01 21:00:00', nullable: true),
        'sort_by' => new OA\Property(property: 'sort_by', type: 'string', enum: ['title', 'deadline'], example: 'title', nullable: true),
        'sort_order' => new OA\Property(property: 'sort_order', type: 'string', enum: ['asc', 'desc'], example: 'asc', nullable: true),
        'active_only' => new OA\Property(property: 'active_only', type: 'boolean', example: false, nullable: true),
    ]
)]
class TaskFilterRequest extends FormRequest
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
            'title' => ['nullable', 'string'],
            'priority' => ['nullable', 'array'],
            'priority.*' => [Rule::in(PriorityTaskEnum::values())],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'deadline_from' => ['nullable', 'date'],
            'deadline_to' => ['nullable', 'date', 'after_or_equal:deadline_from'],
            'sort_by' => ['nullable', 'string', 'in:title,deadline,priority'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            'active_only' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'active_only' => filter_var($this->input('active_only'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'priority' => explode(',', $this->input('priority')),
            'tags' => explode(',', $this->input('tags')),
        ]);
    }
}
