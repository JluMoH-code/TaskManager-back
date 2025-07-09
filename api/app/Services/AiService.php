<?php

namespace App\Services;

use Http;
use Log;

class AiService 
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct() 
    {
        $this->apiKey = config("ai.api_key");
        $this->apiUrl = config("ai.api_url");
        $this->model = config("ai.model");
    }

    public function sendRequest(string $content) 
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->apiKey,
                "Content-Type" => "application/json",
            ])->post(
                $this->apiUrl,
                [
                    "model" => $this->model,
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $content,
                        ],
                    ],
                ],
            );

            return $response;
        } catch (\Exception $e) {
            Log::error('AI Service general Exception: ' . $e->getMessage());
        }
    }
}