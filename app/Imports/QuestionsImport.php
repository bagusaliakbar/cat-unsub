<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Check if required fields exist
            if (empty($row['teks_soal']) || empty($row['mata_ujian_id'])) {
                continue;
            }

            $type = $row['tipe_soal'] ?? 'multiple_choice';
            $type = in_array($type, ['multiple_choice', 'essay']) ? $type : 'multiple_choice';

            $difficulty = $row['tingkat_kesulitan'] ?? 'medium';
            $difficulty = in_array($difficulty, ['easy', 'medium', 'hard']) ? $difficulty : 'medium';

            $question = Question::create([
                'category_id' => $row['mata_ujian_id'],
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
