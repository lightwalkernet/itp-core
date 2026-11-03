<?php

namespace Itp\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Itp\Core\Models\Wildcard;

/**
 * @extends Factory<Wildcard>
 */
class WildcardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Wildcard>
     */
    protected $model = Wildcard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'why' => 'Why '.fake()->words(5, true).'?',
        ];
    }
}
