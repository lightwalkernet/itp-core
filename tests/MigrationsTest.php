<?php

use Illuminate\Support\Facades\Schema;
use Itp\Core\ItpCoreServiceProvider;

dataset('shared tables', ['users', 'itps', 'universal_itps', 'wildcards']);

it('gives every shared table the sync columns and timestamps', function (string $table) {
    expect(Schema::hasColumns($table, ['id', 'sync_seq', 'sync_dirty', 'sync_rev', 'deleted_at', 'created_at', 'updated_at']))
        ->toBeTrue();
})->with('shared tables');

it('gives both ITP tables the same content columns', function () {
    $content = ['title', 'description', 'image_path', 'attunement_text', 'attunement_timings', 'attunement_audio_path',
        'breath11_lines', 'breath11_timings', 'breath11_audio_path', 'quantum_whys'];

    expect(Schema::hasColumns('itps', $content))->toBeTrue()
        ->and(Schema::hasColumns('universal_itps', $content))->toBeTrue();
});

it('rolls back and migrates again cleanly', function () {
    $migrator = app('migrator');
    $path = ItpCoreServiceProvider::migrationsPath();

    $migrator->reset([$path]);

    foreach (['users', 'itps', 'universal_itps', 'wildcards'] as $table) {
        expect(Schema::hasTable($table))->toBeFalse();
    }

    $migrator->run([$path]);

    foreach (['users', 'itps', 'universal_itps', 'wildcards'] as $table) {
        expect(Schema::hasTable($table))->toBeTrue();
    }
});

it('does not register its migrations with the migrator', function () {
    $core = realpath(ItpCoreServiceProvider::migrationsPath());

    $paths = array_map(fn (string $path) => realpath($path) ?: $path, app('migrator')->paths());

    expect($paths)->not->toContain($core);
});
