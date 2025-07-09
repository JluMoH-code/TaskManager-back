<?php

namespace App\Services;

use App\Http\Requests\TagGenerateAiRequest;
use App\Http\Requests\TagSearchRequest;
use App\Models\User;

class TagService 
{
    public function __construct(private AiService $aiService) {}

    public function getTagsForUser(TagSearchRequest $request, User $user)
    {
        $query = $user->tags();

        if ($request->filled('search')) {
            $query->where('title', 'ilike', $request->search .'%');
        }

        $tags = $query->orderBy('id')->get();
        return $tags;
    }

    public function generateAiTags(TagGenerateAiRequest $request)
    {
        $prompt = $this->buildTagGenerationPrompt($request->title, $request->description);
        
        try {
            $aiResponse = $this->aiService->sendRequest($prompt);
            $generatedContent = data_get($aiResponse, 'choices.0.message.content', '');

            if (empty($generatedContent)) {
                return [];
            }

            $decodedTags = json_decode($generatedContent, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedTags)) {
                \Log::warning("AI вернул некорректный JSON: " . $generatedContent);
                return [];
            }

            $tags = array_filter($decodedTags, fn($tag) => is_string($tag));

            return array_values($tags);
        } catch (\Exception $e) {
            \Log::error("Failed to generate AI tags for task: '{$request->title}'. Error: " . $e->getMessage());
            return false;
        }
    }

    private function buildTagGenerationPrompt(string $title, string $description = null) 
    {
        $prompt = "Generate a list of tags in the same language as the title for the following task: '". $title ."'. ";

        if ($description) {
            $prompt .= "Additional description: ". $description .". ";
        }
        
        $prompt .= "Requirements: 
        Each tag must contain no more than two words. 
        The number of tags must be between 1 and 3. 
        Tags must be relevant and specific to the task. 
        Return the result as a strictly valid JSON array of strings, with no explanation or extra text. 
        Do not use markdown style.
        Example format (for Russian): ['тег1', 'тег2']";
        
        return $prompt;
    }
}