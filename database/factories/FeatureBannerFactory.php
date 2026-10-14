<?php

namespace Database\Factories;

use App\Models\FeatureBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeatureBanner>
 */
class FeatureBannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'eyebrow' => 'KEUNGGULAN ROFIQ TOUR',
            'description' => fake()->paragraph(),
            'image_path' => 'images/features/manasik.webp',
            'sort_order' => 0,
            'is_published' => false,
        ];
    }
}
