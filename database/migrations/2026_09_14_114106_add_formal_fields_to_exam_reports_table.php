<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_reports', function (Blueprint $table) {
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('exam_materials')->nullable();
            $table->string('committee_name')->nullable();
            $table->string('witness_1')->nullable();
            $table->string('witness_2')->nullable();
            $table->string('witness_3')->nullable();
            $table->string('witness_4')->nullable();
            $table->string('witness_5')->nullable();
            $table->string('witness_6')->nullable();
            $table->string('witness_7')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_reports', function (Blueprint $table) {
            $table->dropColumn([
                'village',
                'district',
                'reference_number',
                'exam_materials',
                'committee_name',
                'witness_1',
                'witness_2',
                'witness_3',
                'witness_4',
                'witness_5',
                'witness_6',
                'witness_7',
            ]);
        });
    }
};
