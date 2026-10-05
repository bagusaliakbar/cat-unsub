<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Option;
use App\Models\QuestionCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        $validCategoryIds = QuestionCategory::pluck('id')->toArray();
        $fallbackCategoryId = !empty($validCategoryIds) ? $validCategoryIds[0] : null;

        if (!$fallbackCategoryId && $rows->isNotEmpty()) {
            $createdCat = QuestionCategory::create([
                'name' => 'Umum',
                'description' => 'Kategori default hasil import',
            ]);
            $fallbackCategoryId = $createdCat->id;
            $validCategoryIds[] = $fallbackCategoryId;
        }

        foreach ($rows as $row) {
            // Check if required fields exist
            if (empty($row['teks_soal'])) {
                continue;
            }

            $catId = $row['mata_ujian_id'] ?? null;
            if (!$catId || !in_array($catId, $validCategoryIds)) {
                $catId = $fallbackCategoryId;
            }

            $type = $row['tipe_soal'] ?? 'multiple_choice';
            $type = in_array($type, ['multiple_choice', 'essay']) ? $type : 'multiple_choice';

            $difficulty = $row['tingkat_kesulitan'] ?? 'medium';
            $difficulty = in_array($difficulty, ['easy', 'medium', 'hard']) ? $difficulty : 'medium';

            $question = Question::create([
                'category_id' => $catId,
                'type' => $type,
                'difficulty' => $difficulty,
                'points' => $row['poin'] ?? 1,
                'text' => $row['teks_soal'],
                'is_active' => 1,
            ]);

            if ($type === 'multiple_choice') {
                $optionsMap = [
                    'A' => ['text' => $row['opsi_a'] ?? null, 'correct' => $row['benar_a'] ?? 0],
                    'B' => ['text' => $row['opsi_b'] ?? null, 'correct' => $row['benar_b'] ?? 0],
                    'C' => ['text' => $row['opsi_c'] ?? null, 'correct' => $row['benar_c'] ?? 0],
                    'D' => ['text' => $row['opsi_d'] ?? null, 'correct' => $row['benar_d'] ?? 0],
                    'E' => ['text' => $row['opsi_e'] ?? null, 'correct' => $row['benar_e'] ?? 0],
                ];

                foreach ($optionsMap as $opt) {
                    if (!empty($opt['text'])) {
                        Option::create([
                            'question_id' => $question->id,
                            'text' => $opt['text'],
                            'is_correct' => $opt['correct'] ? 1 : 0,
                        ]);
                    }
                }
            }
        }
    }
}
