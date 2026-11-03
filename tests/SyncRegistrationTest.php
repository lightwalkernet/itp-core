<?php

use Itp\Core\ItpCoreServiceProvider;
use Itp\Core\Models\Itp;
use Itp\Core\Models\UniversalItp;
use Itp\Core\Models\User;
use Itp\Core\Models\Wildcard;
use Lightwalker\Sync\SyncRegistry;

it('registers the four types in pull order', function () {
    expect(config('sync.models'))->toBe([
        'users' => ['model' => User::class, 'scope' => 'user', 'fields' => [], 'owner_key' => 'id'],
        'itps' => ['model' => Itp::class, 'scope' => 'user', 'fields' => []],
        'universal_itps' => ['model' => UniversalItp::class, 'scope' => 'global', 'pull_every' => '1 day'],
        'wildcards' => ['model' => Wildcard::class, 'scope' => 'global', 'pull_every' => '1 day'],
    ]);
});

it('makes every type pull-only', function (string $model) {
    expect(app(SyncRegistry::class)->isPullOnly($model))->toBeTrue();
})->with([User::class, Itp::class, UniversalItp::class, Wildcard::class]);

it('keeps a type the host has already set, and the host\'s own types after its own', function () {
    $hostItps = ['model' => Itp::class, 'scope' => 'user', 'fields' => ['title']];
    $hostNotes = ['model' => stdClass::class, 'scope' => 'user', 'fields' => ['body']];

    config(['sync.models' => ['itps' => $hostItps, 'device_notes' => $hostNotes]]);

    (new ItpCoreServiceProvider($this->app))->register();

    expect(array_keys(config('sync.models')))->toBe(['users', 'itps', 'universal_itps', 'wildcards', 'device_notes'])
        ->and(config('sync.models.itps'))->toBe($hostItps)
        ->and(config('sync.models.device_notes'))->toBe($hostNotes);
});
