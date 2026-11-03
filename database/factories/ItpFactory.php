<?php

namespace Itp\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Itp\Core\Models\Itp;
use Itp\Core\Models\User;

/**
 * @extends Factory<Itp>
 */
class ItpFactory extends Factory
{
    use DefinesItpContent;

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Itp>
     */
    protected $model = Itp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            ...$this->itpContent(),
        ];
    }

    /**
     * Give the ITP to an existing user.
     */
    public function forUser(User $user): static
    {
        return $this->for($user);
    }
}
