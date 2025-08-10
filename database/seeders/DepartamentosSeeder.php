<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departamentos')->insert([
            ['nome' => 'SUPORTE', 'descricao' => 'departamento do suporte'],
            ['nome' => 'DESENVOLVIMENTO', 'descricao' => 'departamento do desenvolvimento'],
        ]);
    }
}
