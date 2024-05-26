<?php

namespace Database\Factories;

use App\Models\Academy;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    private static int $order = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!self::$order) {
            self::$order = $this->autoIncrement();
        }

        return [
            'academy_id' => fake()->randomElement([null, Academy::factory()]),
            'name' => fake()->name(),
            'access_id' => 'student'.self::$order++,
            'status' => 0,
            'password' => Hash::make('1234'),
            'remember_token' => Str::random(10),
            'parents_phone' => $this->faker->numerify('010########'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function autoIncrement(): int
    {
        $student = Student::orderBy('id', 'desc')->first();
        return $student ? $student->id + 1 : 1;
    }
}
