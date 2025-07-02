<?php

namespace App\Enums;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "low: Минимальный, medium: Средний, high: Наивысший", type: "string",
    enum: ['Минимальный', 'Средний', 'Наивысший'],
    x: ["enum-varnames" => ["LOW", "MEDIUM" , "HIGH"]],
)]
enum PriorityTaskEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function sortWeights(): array
    {
        return [
            'low' => 1,
            'medium' => 2,
            'high' => 3,
        ];
    }
}
