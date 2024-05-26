<?php

namespace Database\Factories;

use App\Models\Academy;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademyFactory extends Factory
{
    private static int $order = 0;

    /**
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!self::$order) {
            self::$order = $this->autoIncrement();
        }

        return [
            'name' => '테스트_'.self::$order++.' 학원',
            'zipcode' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'address2' => $this->faker->streetAddress(),
            'phone' => $this->faker->numerify('111-####-####'),
            'staff_phone' => $this->faker->numerify('111-####-####'),
            'staff_name' => $this->faker->name(),
            'staff_email' => $this->faker->unique()->safeEmail(),
            'extra' => [],
        ];
    }

    public function configure(): AcademyFactory
    {
        return $this->afterCreating(function (Academy $academy) {
            // 태그
            $academy->syncTagsWithType([fake()->word(), fake()->word()], 'admin.academies');
        });
    }

    public function autoIncrement(): int
    {
        $academy = Academy::orderBy('id', 'desc')->first();
        return $academy ? $academy->id + 1 : 1;
    }
}
