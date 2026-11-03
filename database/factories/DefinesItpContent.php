<?php

namespace Itp\Core\Database\Factories;

/**
 * Default state for the `ItpContent` columns, shared by the ITP factories.
 */
trait DefinesItpContent
{
    /**
     * @return array<string, mixed>
     */
    protected function itpContent(): array
    {
        $sentence = fake()->sentence();

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'image_path' => null,
            'attunement_text' => $sentence,
            'attunement_timings' => [
                [
                    'start' => 0.0,
                    'end' => 4.0,
                    'text' => $sentence,
                    'words' => [['word' => strtok($sentence, ' '), 'start' => 0.0, 'end' => 0.5]],
                ],
            ],
            'attunement_audio_path' => 'itps/'.fake()->uuid().'/attunement.m4a',
            'breath11_lines' => [['order' => 1, 'text' => fake()->sentence()]],
            'breath11_timings' => null,
            'breath11_audio_path' => null,
            'quantum_whys' => ['Why '.fake()->words(4, true).'?'],
        ];
    }
}
