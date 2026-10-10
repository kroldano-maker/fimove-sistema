<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('genero', 1)->nullable()->after('fecha_nacimiento');
            $table->string('estado_civil', 20)->nullable()->after('genero');
            $table->string('profesion', 100)->nullable()->after('estado_civil');
            $table->string('ocupacion', 100)->nullable()->after('profesion');
            $table->string('lateralidad', 10)->nullable()->after('ocupacion');
        });

        if (Schema::hasColumn('pacientes', 'dpi')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->dropColumn('dpi');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('dpi', 13)->nullable();
            $table->dropColumn(['genero', 'estado_civil', 'profesion', 'ocupacion', 'lateralidad']);
        });
    }
};