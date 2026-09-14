<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): \Illuminate\Support\Collection
    {
        return Question::with('options')->get();
    }

    public function headings(): array
    {
        return [
            'Mata Ujian ID',
            'Tipe Soal',
            'Tingkat Kesulitan',
            'Poin',
            'Teks Soal',
            'Opsi A', 'Benar A',
            'Opsi B', 'Benar B',
            'Opsi C', 'Benar C',
            'Opsi D', 'Benar D',
            'Opsi E', 'Benar E',
        ];
    }

    public function map($question): array
    {
        $row = [
            $question->category_id,
            $question->type,
            $question->difficulty,
            $question->points,
            $question->text,
        ];

        if ($question->type === 'multiple_choice') {
            foreach ($question->options as $option) {
                $row[] = $option->text;
                $row[] = $option->is_correct ? 1 : 0;
            }
        }
        
        // Pad the array to ensure it matches the 15 columns length
        return array_pad($row, 15, '');
    }
}
