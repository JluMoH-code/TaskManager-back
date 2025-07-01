<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'integer', example: '1'),
        'name' => new OA\Property(property:'name', type:'string', example:'Name'),
        'email' => new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@test.com'),
    ]
)]
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name" => $this->name,
            "email" => $this->email,
        ];
    }
}
