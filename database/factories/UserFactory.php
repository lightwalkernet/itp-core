<?php

namespace Itp\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Itp\Core\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the user has not completed their profile.
     */
    public function withoutFirstName(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => null,
        ]);
    }
}
