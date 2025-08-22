<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = fake()->image('public/storage/images');

        $name = basename($path);

        return [
            // 'product_id'=> Product::inRandomOrder()->pluck('id')->first(),
            'name' => $name,
        ];
    }
}
