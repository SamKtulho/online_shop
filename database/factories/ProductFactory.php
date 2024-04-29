<?php

namespace Database\Factories;

use Domains\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'thumbnail' => $this->faker->getImage(
                base_path('/tests/Fixtures/images/products'),
                storage_path('/app/public/images/products')
            ),
            'price' => $this->faker->numberBetween(10000, 1000000),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
            'is_on_main_page' => $this->faker->boolean(),
            'sorting' => $this->faker->numberBetween(1, 999)
        ];
    }
}
