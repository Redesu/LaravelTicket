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

        Schema::create('chamado_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamado_id')->constrained('chamados')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->text('descricao');
            $table->enum('tipo', ['comment', 'edit', 'solution'])->default('comment');
            $table->json('changes')->nullable(); // Store edit changes
            $table->timestamps();

            $table->index(['chamado_id', 'created_at']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamado_comentarios');

        Schema::table('chamados', function (Blueprint $table) {
            $table->text('comentarios')->nullable();
            $table->text('solucao')->nullable();
        });
    }
};
