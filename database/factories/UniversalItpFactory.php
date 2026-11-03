<?php

namespace Itp\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Itp\Core\Enums\UniversalItpKind;
use Itp\Core\Models\UniversalItp;

/**
 * @extends Factory<UniversalItp>
 */
class UniversalItpFactory extends Factory
{
    use DefinesItpContent;

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<UniversalItp>
     */
    protected $model = UniversalItp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = $this->itpContent();

        return [
            'slug' => Str::slug($content['title']).'-'.Str::lower(Str::random(6)),
            'kind' => UniversalItpKind::Universal,
            'sort_order' => null,
            ...$content,
        ];
    }

    /**
     * Indicate that the ITP is a base piece, such as the Introduction.
     */
    public function base(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => UniversalItpKind::Base,
        ]);
    }
}
