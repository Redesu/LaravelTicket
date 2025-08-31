<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChamadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chamados')->insert(
            [
                ['titulo' => 'Problema com login', 'descricao' => 'Não consigo acessar minha conta', 'status' => 'Aberto', 'prioridade' => 'Alta', 'user_id' => 1, 'created_by' => 1, 'categoria_id' => 1, 'departamento_id' => 1, 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
                ['titulo' => 'Dúvida sobre o sistema', 'descricao' => 'Como faço para resetar minha senha?', 'status' => 'Em andamento', 'prioridade' => 'Baixa', 'user_id' => 1, 'created_by' => 1, 'categoria_id' => 2, 'departamento_id' => 2, 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
                ['titulo' => 'Correção de bug', 'descricao' => 'O sistema está apresentando erro ao salvar dados', 'status' => 'Finalizado', 'prioridade' => 'Urgente', 'user_id' => 1, 'created_by' => 1, 'categoria_id' => 3, 'departamento_id' => 1, 'created_at' => null, 'updated_at' => null, 'deleted_at' => null]
            ]
        );
    }
}
