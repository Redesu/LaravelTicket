<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chamado>
 */
class ChamadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(),
            'descricao' => fake()->paragraph(),
            'status' => fake()->randomElement(['Aberto', 'Em andamento', 'Finalizado']),
            'categoria_id' => fake()->numberBetween(1, 3),
            'departamento_id' => fake()->numberBetween(1, 2),
            'user_id' => fake()->numberBetween(1, 2),
            'prioridade' => fake()->randomElement(['Urgente', 'Alta', 'Média', 'Baixa']),
        ];
    }
}
