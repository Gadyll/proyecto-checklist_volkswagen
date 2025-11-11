<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            if (!Schema::hasColumn('revisiones', 'revision_1')) {
                $table->string('revision_1', 5)->nullable();
            }
            if (!Schema::hasColumn('revisiones', 'revision_2')) {
                $table->string('revision_2', 5)->nullable();
            }
            if (!Schema::hasColumn('revisiones', 'revision_3')) {
                $table->string('revision_3', 5)->nullable();
            }
            if (!Schema::hasColumn('revisiones', 'comentario')) {
                $table->text('comentario')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            $table->dropColumn(['revision_1', 'revision_2', 'revision_3', 'comentario']);
        });
    }
};
