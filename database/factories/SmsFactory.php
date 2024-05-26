<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sms>
 */
class SmsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => '문자발송',
            'send_phone' => fake()->numerify('111-####-####'),
            'dest_phone' => fake()->numerify('111-####-####'),
            'template_code' => Str::random(10),
            'msg' => fake()->text(200),
        ];
    }
}
