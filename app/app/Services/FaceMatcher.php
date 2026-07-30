<?php

namespace App\Services;

class FaceMatcher
{
    public static function parse($value): array
    {
        if (is_array($value)) {
            return array_values(array_map('floatval', $value));
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return array_values(array_map('floatval', $decoded));
            }
        }

        return [];
    }

    public static function normalize(array $vector): array
    {
        $sum = 0.0;

        foreach ($vector as $v) {
            $sum += $v * $v;
        }

        $length = sqrt($sum);

        if ($length <= 0.0) {
            return [];
        }

        return array_map(function ($v) use ($length) {
            return $v / $length;
        }, $vector);
    }

    public static function similarity(array $a, array $b): float
    {
        if (count($a) === 0 || count($b) === 0) {
            return -1.0;
        }

        // 128-d আর 192-d একসাথে compare করা যাবে না
        if (count($a) !== count($b)) {
            return -1.0;
        }

        $a = self::normalize($a);
        $b = self::normalize($b);

        if (count($a) === 0 || count($b) === 0) {
            return -1.0;
        }

        $dot = 0.0;

        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
        }

        return (float) $dot;
    }

    public static function findBestMatch(array $newEmbedding, $students): array
    {
        $bestStudent = null;
        $bestScore = -1.0;

        foreach ($students as $student) {
            $oldEmbedding = self::parse($student->face_embedding);

            // empty [] হলে skip
            if (count($oldEmbedding) === 0) {
                continue;
            }

            // length different হলে skip
            // যেমন old web 128 আর Android 192
            if (count($oldEmbedding) !== count($newEmbedding)) {
                continue;
            }

            $score = self::similarity($newEmbedding, $oldEmbedding);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestStudent = $student;
            }
        }

        return [
            'student' => $bestStudent,
            'score'   => $bestScore,
        ];
    }
}
