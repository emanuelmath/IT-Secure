<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Empresa::class;
    public function definition(): array
    {
        return [
            'nombre' => fake()->word(),
            'nit' => fake()->numerify('####################')
        ];
    }
}
