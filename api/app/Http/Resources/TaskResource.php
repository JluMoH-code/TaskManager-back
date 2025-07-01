<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Openapi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'integer', example: '1'),
        'title' => new OA\Property(property:'title', type:'string', example:'title'),
        'description' => new OA\Property(property: 'description', type: 'string', example: 'description'),
        'deadline' => new OA\Property(property: 'deadline', type: 'datetime', example: '2025-01-01 21:00:00'),
        'active' => new OA\Property(property: 'active', type: 'boolean', example: 'true'),
    ]
)]
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'active' => $this->active,
        ];
    }
}
