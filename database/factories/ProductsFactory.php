<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
  /**
    * The name of the factory's corresponding model.
    *
    * @var string
  */
  protected $model = Products::class;

  /**
    * Define the model's default state.
    *
    * @return array
  */
  public function definition()
  {
    return [
      'name' => $this->faker->word,
      'description' => $this->faker->paragraph(1),
      'quantity' => $this->faker->numberBetween(1, 10),
      'status' => $this->faker->randomElement([Products::PRODUCTO_DISPONIBLE, Products::PRODUCTO_NO_DISPONIBLE]),
      'image' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
      // 'seller_id' => User::inRandomOrder()->first()->id,
      'seller_id' => User::all()->random()->id,
    ];
  }
}