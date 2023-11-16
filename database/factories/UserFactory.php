<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * Faker documentation
         * https://fakerphp.github.io/
         */
        return [
            'name' => fake()->firstName().' '.fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('qwerasdf'), // password
            'remember_token' => Str::random(10),
            'status' => 'active',
            'user_type' => 2,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function isStudent()
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 2,
        ]);
    }

    public function isTeacher()
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 3,
        ]);
    }
}
