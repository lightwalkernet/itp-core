<?php

namespace Itp\Core\Schema;

use Illuminate\Database\Schema\Blueprint;

/**
 * The playable content of an ITP, shared by `itps` and `universal_itps`. One row holds
 * everything a player needs, so a pull can never deliver half an ITP.
 */
class ItpContent
{
    /**
     * Content columns stored as JSON and cast to arrays.
     *
     * @var list<string>
     */
    public const array JSON_COLUMNS = [
        'attunement_timings',
        'breath11_lines',
        'breath11_timings',
        'quantum_whys',
    ];

    /**
     * Every content column.
     *
     * @var list<string>
     */
    public const array COLUMNS = [
        'title',
        'description',
        'image_path',
        'attunement_text',
        'attunement_timings',
        'attunement_audio_path',
        'breath11_lines',
        'breath11_timings',
        'breath11_audio_path',
        'quantum_whys',
    ];

    /**
     * Add the content columns. Portable builder types only: they run on MySQL and device SQLite.
     *
     * - `attunement_timings`, `breath11_timings`: `[{start, end, text, words: [{word, start, end}]}]`
     * - `breath11_lines`: `[{order, text}]`
     * - `quantum_whys`: a list of "Why …?" strings
     * - `*_audio_path`, `image_path`: R2 object paths
     */
    public static function columns(Blueprint $table): void
    {
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('image_path')->nullable();
        $table->longText('attunement_text')->nullable();
        $table->json('attunement_timings')->nullable();
        $table->string('attunement_audio_path')->nullable();
        $table->json('breath11_lines')->nullable();
        $table->json('breath11_timings')->nullable();
        $table->string('breath11_audio_path')->nullable();
        $table->json('quantum_whys')->nullable();
    }
}
