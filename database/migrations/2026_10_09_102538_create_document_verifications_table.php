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
        Schema::create('document_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique()->index();
            $table->string('document_type')->default('question_bank');
            $table->string('title');
            $table->string('category_name')->nullable();
            $table->integer('total_questions')->default(0);
            $table->string('mode')->default('without_keys'); // 'with_keys' or 'without_keys'
            $table->string('printed_by')->nullable();
            $table->string('checksum')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_verifications');
    }
};
