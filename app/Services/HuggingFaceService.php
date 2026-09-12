<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HuggingFaceService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api-inference.huggingface.co/models/google/gemma-1.1-2b-it';
        $this->apiKey = env('HUGGING_FACE_API_KEY');
    }

    public function processInput($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
        ])->post($this->apiUrl, [
            'inputs' => $prompt,
        ]);

        return $response->json();
    }
}
