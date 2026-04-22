<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogImageService
{
    /** Colour themes per category */
    protected array $themes = [
        'Laravel' => ['bg_top' => [26, 8, 0], 'bg_bot' => [45, 16, 16], 'accent' => [240, 83, 64]],
        'Vue.js' => ['bg_top' => [13, 31, 23], 'bg_bot' => [10, 35, 24], 'accent' => [66, 184, 131]],
        'Flutter' => ['bg_top' => [7, 22, 36], 'bg_bot' => [13, 32, 53], 'accent' => [84, 197, 248]],
        'Python' => ['bg_top' => [17, 24, 8], 'bg_bot' => [26, 36, 16], 'accent' => [255, 211, 67]],
        'PHP' => ['bg_top' => [14, 14, 32], 'bg_bot' => [22, 22, 52], 'accent' => [119, 123, 179]],
        'Mobile' => ['bg_top' => [7, 22, 36], 'bg_bot' => [13, 32, 53], 'accent' => [84, 197, 248]],
        'Full Stack' => ['bg_top' => [14, 14, 18], 'bg_bot' => [26, 26, 34], 'accent' => [240, 165, 0]],
        'default' => ['bg_top' => [14, 14, 14], 'bg_bot' => [26, 26, 26], 'accent' => [240, 165, 0]],
    ];

    /**
     * Generate a cover image and return the storage-relative path.
     * Returns null if GD is not available.
     */
    public function generate(string $title, string $category): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            Log::warning('BlogBot: GD extension not available, skipping image generation.');

            return null;
        }

        $theme = $this->themes[$category] ?? $this->themes['default'];
        $w = 900;
        $h = 500;

        $img = imagecreatetruecolor($w, $h);

        // Gradient background
        $topR = $theme['bg_top'][0];
        $topG = $theme['bg_top'][1];
        $topB = $theme['bg_top'][2];
        $botR = $theme['bg_bot'][0];
        $botG = $theme['bg_bot'][1];
        $botB = $theme['bg_bot'][2];

        for ($y = 0; $y < $h; $y++) {
            $t = $y / $h;
            $r = (int) ($topR * (1 - $t) + $botR * $t);
            $g = (int) ($topG * (1 - $t) + $botG * $t);
            $b = (int) ($topB * (1 - $t) + $botB * $t);
            $line = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $line);
        }

        $accentR = $theme['accent'][0];
        $accentG = $theme['accent'][1];
        $accentB = $theme['accent'][2];

        // Decorative dots
        srand(crc32($title));
        $dotColor = imagecolorallocatealpha($img, $accentR, $accentG, $accentB, 90);
        for ($i = 0; $i < 14; $i++) {
            $x = rand(40, $w - 40);
            $y = rand(40, $h - 40);
            $r = rand(3, 9);
            imagefilledellipse($img, $x, $y, $r * 2, $r * 2, $dotColor);
        }

        // Accent bar
        $accentColor = imagecolorallocate($img, $accentR, $accentG, $accentB);
        imagefilledrectangle($img, 60, $h / 2 - 82, 110, $h / 2 - 76, $accentColor);

        // Category label
        $white = imagecolorallocate($img, 255, 255, 255);
        $grey = imagecolorallocate($img, 156, 163, 175);
        $catUpper = mb_strtoupper($category);
        imagestring($img, 3, 60, $h / 2 - 65, $catUpper, $accentColor);

        // Title (word-wrapped, up to 3 lines at font size 5)
        $lines = $this->wrapText($title, 28);
        $y = $h / 2 - 30;
        foreach (array_slice($lines, 0, 3) as $line) {
            imagestring($img, 5, 60, $y, $line, $white);
            $y += 22;
        }

        // Bottom accent strip
        imagefilledrectangle($img, 0, $h - 5, $w, $h, $accentColor);

        // Save
        $filename = 'blog/' . Str::slug($category . '-' . Str::words($title, 4, '')) . '-' . time() . '.jpg';
        $fullPath = storage_path('app/public/' . $filename);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        imagejpeg($img, $fullPath, 88);
        imagedestroy($img);

        return $filename;
    }

    /**
     * @return array<string>
     */
    protected function wrapText(string $text, int $maxChars): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            if (strlen($current . ' ' . $word) <= $maxChars) {
                $current = ltrim($current . ' ' . $word);
            } else {
                if ($current !== '') {
                    $lines[] = $current;
                }
                $current = $word;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines;
    }
}
