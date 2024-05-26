<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class AdminFactory extends Factory
{
    use HasRoles;

    private static int $order = 0;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!self::$order) {
            self::$order = $this->autoIncrement();
        }

        return [
            'name' => fake()->name(),
            'access_id' => 'admin_test_'.self::$order++,
            'password' => Hash::make('1234'), // password
            'remember_token' => Str::random(60),
        ];
    }

    public function autoIncrement(): int
    {
        $admin = Admin::orderBy('id', 'desc')->first();
        return $admin ? $admin->id + 1 : 1;
    }
}
