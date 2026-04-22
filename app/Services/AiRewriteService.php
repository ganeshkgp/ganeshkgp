<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiRewriteService
{
    /**
     * OpenRouter API endpoint (OpenAI-compatible).
     * Swap the model string here to try any other free model on openrouter.ai/models?q=free
     */
    protected string $endpoint = 'https://openrouter.ai/api/v1/chat/completions';

    protected string $model = 'minimax/minimax-m2.5:free';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.key', '');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Rewrite an article in Ganesh's voice using a free OpenRouter model.
     *
     * @return array{title: string, excerpt: string, content: string}
     */
    public function rewrite(string $title, string $rawContent, string $category): array
    {
        if (! $this->isConfigured()) {
            return $this->fallback($title, $rawContent);
        }

        $plainText = mb_substr(strip_tags($rawContent), 0, 4000);

        $prompt = <<<PROMPT
You are writing a blog post for T Ganesh's personal developer portfolio (Kharagpur, West Bengal, India).
Ganesh specialises in PHP/Laravel, Vue.js, Flutter, Python, and REST APIs.

Rewrite the article below in Ganesh's voice: opinionated, practical, occasionally referencing the Indian dev ecosystem. Do NOT copy verbatim.

Respond with ONLY a valid JSON object — no markdown fences, no extra text — with these exact keys:
- "title": punchy rewritten title, max 80 chars
- "excerpt": 1-2 sentence teaser, max 160 chars
- "content": full article as clean HTML using only <p> <h2> <h3> <ul> <ol> <li> <strong> <em> <code> <pre> <blockquote> tags, 500-800 words

Original title: {$title}
Category: {$category}

Article text:
{$plainText}
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'https://portfolio.local'),
                'X-Title' => config('app.name', 'Portfolio BlogBot'),
            ])->timeout(90)->post($this->endpoint, [
                'model' => $this->model,
                'max_tokens' => 2048,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (! $response->successful()) {
                throw new \RuntimeException("OpenRouter {$response->status()}: " . $response->body());
            }

            $text = $response->json('choices.0.message.content', '');

            // Strip markdown code fences if the model wraps the JSON
            $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
            $text = preg_replace('/\s*```$/', '', $text);

            $decoded = json_decode(trim($text), true);

            if (! is_array($decoded) || empty($decoded['title']) || empty($decoded['content'])) {
                throw new \RuntimeException('Unexpected JSON structure from model');
            }

            return [
                'title' => $decoded['title'],
                'excerpt' => $decoded['excerpt'] ?? $this->makeExcerpt($rawContent),
                'content' => $decoded['content'],
            ];
        } catch (\Throwable $e) {
            Log::warning("BlogBot: AI rewrite failed for '{$title}': {$e->getMessage()}");

            return $this->fallback($title, $rawContent);
        }
    }

    /** @return array{title: string, excerpt: string, content: string} */
    protected function fallback(string $title, string $rawContent): array
    {
        return [
            'title' => $title,
            'excerpt' => $this->makeExcerpt($rawContent),
            'content' => strip_tags($rawContent, '<p><h2><h3><h4><ul><ol><li><strong><em><code><pre><blockquote><a>'),
        ];
    }

    protected function makeExcerpt(string $html, int $words = 30): string
    {
        $text = preg_replace('/\s+/', ' ', trim(strip_tags($html)));
        $parts = explode(' ', $text);

        return implode(' ', array_slice($parts, 0, $words)) . (count($parts) > $words ? '…' : '');
    }
}
