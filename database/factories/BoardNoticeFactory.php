<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoardNotice>
 */
class BoardNoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_id' => Admin::factory(),
            'scope' => $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7]),
            'category' => $this->faker->randomElement(array_column(dbcode('board_notices.category'), 'name')),
            'sub_category' => '',
            'title' => fake()->sentence(),
            'contents' => fake()->text(),
            'published_at' => $this->faker->randomElement([null, fake()->datetime()->format('Y-m-d H:i:s')]),
        ];
    }
}
