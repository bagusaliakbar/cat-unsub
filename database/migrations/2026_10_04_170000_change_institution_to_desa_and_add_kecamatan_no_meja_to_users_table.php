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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'institution') && !Schema::hasColumn('users', 'desa')) {
                $table->renameColumn('institution', 'desa');
            } elseif (!Schema::hasColumn('users', 'desa')) {
                $table->string('desa')->nullable()->after('address');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('desa');
            }
            if (!Schema::hasColumn('users', 'no_meja')) {
                $table->string('no_meja')->nullable()->after('kecamatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'no_meja')) {
                $table->dropColumn('no_meja');
            }
            if (Schema::hasColumn('users', 'kecamatan')) {
                $table->dropColumn('kecamatan');
            }
            if (Schema::hasColumn('users', 'desa')) {
                $table->renameColumn('desa', 'institution');
            }
        });
    }
};
