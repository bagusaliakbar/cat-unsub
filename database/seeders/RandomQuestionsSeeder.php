<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionCategory;
use App\Models\Question;
use App\Models\Option;
use Faker\Factory as Faker;

class RandomQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create some categories if not exist
        $categories = ['TIU (Tes Intelegensia Umum)', 'TWK (Tes Wawasan Kebangsaan)', 'TKP (Tes Karakteristik Pribadi)', 'Tes Kompetensi Bidang'];
        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = QuestionCategory::firstOrCreate(['name' => $cat]);
            $categoryIds[] = $category->id;
        }

        $difficulties = ['easy', 'medium', 'hard'];

        $this->command->info('Creating 100 Multiple Choice Questions...');
        for ($i = 0; $i < 100; $i++) {
            $difficulty = $faker->randomElement($difficulties);
            $points = $difficulty === 'hard' ? 5 : ($difficulty === 'medium' ? 3 : 1);

            $question = Question::create([
                'category_id' => $faker->randomElement($categoryIds),
                'text' => 'Soal Pilihan Ganda ' . ($i + 1) . ': ' . $faker->realText(100) . '?',
                'type' => 'multiple_choice',
                'difficulty' => $difficulty,
                'points' => $points,
                'is_active' => true,
            ]);

            // Create 5 options (A, B, C, D, E)
            $correctIndex = rand(0, 4);
            for ($j = 0; $j < 5; $j++) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $faker->sentence(rand(3, 8)),
                    'is_correct' => $j === $correctIndex,
                ]);
            }
        }

        $this->command->info('Creating 50 Essay Questions...');
        for ($i = 0; $i < 50; $i++) {
            $difficulty = $faker->randomElement($difficulties);
            $points = $difficulty === 'hard' ? 10 : ($difficulty === 'medium' ? 7 : 5);

            Question::create([
                'category_id' => $faker->randomElement($categoryIds),
                'text' => 'Soal Esai ' . ($i + 1) . ': Jelaskan ' . $faker->realText(50) . ' dengan lengkap dan detail!',
                'type' => 'essay',
                'difficulty' => $difficulty,
                'points' => $points,
                'is_active' => true,
            ]);
        }

        $this->command->info('Successfully added 150 random questions!');
    }
}
