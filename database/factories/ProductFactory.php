<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 49, 899);

        return [
            'uuid' => (string) Str::uuid(),
            'sku' => strtoupper($this->faker->unique()->bothify('SN-#####')),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'name' => Str::title($name),
            'brand' => $this->faker->randomElement(['SafeNest', 'SecureGuard', 'HomeShield', 'GuardianEye', 'TechSecure', 'SmartGuard']),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => $price + $this->faker->randomFloat(2, 10, 75),
            'currency' => 'USD',
            'rating_average' => $this->faker->randomFloat(1, 3.5, 5),
            'reviews_count' => $this->faker->numberBetween(0, 2500),
            'availability_status' => $this->faker->randomElement(['in_stock', 'preorder', 'backorder']),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(15),
            'warranty_period' => $this->faker->randomElement(['1 year', '2 years', '3 years']),
            'return_policy' => '30-day returns',
            'specifications_snapshot' => [],
            'meta' => [],
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
