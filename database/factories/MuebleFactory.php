<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mueble>
 */
class MuebleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition()
    {
        return [
            'categoria_id' => $this->faker->numberBetween(1, 3), //esto hay que cambiarlo para que sea dinámico
            'nombre'       => ucfirst($this->faker->words(2, true)),
            'descripcion'  => $this->faker->text(200),
            'precio'       => $this->faker->randomFloat(2, 20, 2000),
            'color' => $this->faker->safeColorName(),
            'stock'        => $this->faker->numberBetween(0, 100),
            'novedad'    => $this->faker->boolean(30),
            'materiales'   => $this->faker->randomElement(['Madera', 'Metal', 'Vidrio', 'Aglomerado', 'Pino']),
            'dimensiones'  => $this->faker->numberBetween(50, 200) . 'x' .
                              $this->faker->numberBetween(40, 100) . 'x' .
                              $this->faker->numberBetween(30, 90) . ' cm',
            'activo'       => $this->faker->boolean(90),
        ];
    }
}
