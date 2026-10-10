<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('dpi', 20)->nullable()->change();
        });

        // Los pacientes que se registraron con "0" pasan a quedar sin DPI
        DB::table('pacientes')->where('dpi', '0')->update(['dpi' => null]);
    }

    public function down(): void
    {
        DB::statement("UPDATE pacientes SET dpi = CONCAT('SIN-', id) WHERE dpi IS NULL");

        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('dpi', 20)->nullable(false)->change();
        });
    }
};