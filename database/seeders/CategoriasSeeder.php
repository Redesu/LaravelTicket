<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("categorias")->insert([
            ['nome' => 'SUPORTE', 'created_at' => now()],
            ['nome' => 'DUVIDAS', 'created_at' => now()],
            ['nome' => 'CORREÇÃO', 'created_at' => now()],
        ]);
    }
}
