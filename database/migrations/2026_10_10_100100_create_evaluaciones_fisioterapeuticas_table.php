<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones_fisioterapeuticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('remitido_por', 150)->nullable();
            $table->text('diagnostico_medico')->nullable();

            $table->text('ant_patologicos')->nullable();
            $table->text('ant_quirurgicos')->nullable();
            $table->text('ant_traumaticos')->nullable();
            $table->text('ant_farmacologicos')->nullable();
            $table->text('ant_alergicos')->nullable();
            $table->text('ant_gineco_obstetricos')->nullable();
            $table->text('ant_otros')->nullable();
            $table->text('antecedentes_familiares')->nullable();

            $table->string('fuma', 150)->nullable();
            $table->string('alcohol', 150)->nullable();

            $table->text('motivo_consulta')->nullable();
            $table->text('cronologia_patologia')->nullable();
            $table->text('dolor')->nullable();
            $table->text('palpacion')->nullable();
            $table->text('reflejos_osteotendinosos')->nullable();
            $table->text('sensibilidad')->nullable();

            $table->text('postura_anterior')->nullable();
            $table->text('postura_lateral')->nullable();
            $table->text('postura_posterior')->nullable();

            $table->text('pruebas_especiales')->nullable();
            $table->string('longitud_superiores', 150)->nullable();
            $table->string('longitud_inferiores', 150)->nullable();
            $table->text('examen_muscular')->nullable();
            $table->text('arco_movimiento')->nullable();
            $table->text('examen_retracciones')->nullable();

            $table->text('marcha_apoyo')->nullable();
            $table->text('marcha_balanceo')->nullable();
            $table->text('equilibrio')->nullable();

            $table->text('diagnostico_fisioterapeutico')->nullable();
            $table->text('conducta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_fisioterapeuticas');
    }
};