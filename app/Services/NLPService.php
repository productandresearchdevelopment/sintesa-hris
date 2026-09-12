<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NLPService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api-inference.huggingface.co/models/dbmdz/bert-large-cased-finetuned-conll03-english';
        $this->apiKey = env('HUGGING_FACE_API_KEY');
    }

    public function analyzeInput($input)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
        ])->post($this->apiUrl, ['inputs' => $input]);

        $entities = $response->json();
        return $entities;
    }
}
