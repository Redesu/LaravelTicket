<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('status', ['Aberto', 'Em andamento', 'Finalizado'])->default('Aberto');
            $table->enum('prioridade', ['Baixa', 'Média', 'Alta', 'Urgente'])->default('Baixa');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->text('solucao')->nullable();
            $table->text('comentarios')->nullable();
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->timestamp('data_fechamento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};
