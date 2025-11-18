<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            $table->text('comentario')->nullable()->after('revision_3');
        });
    }

    public function down(): void
    {
        Schema::table('revisiones', function (Blueprint $table) {
            $table->dropColumn('comentario');
        });
    }
};
