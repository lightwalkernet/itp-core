<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Itp\Core\Enums\UniversalItpKind;
use Itp\Core\Models\Itp;
use Itp\Core\Models\UniversalItp;
use Itp\Core\Models\User;
use Itp\Core\Models\Wildcard;

it('uses ULID keys', function (string $model) {
    $record = $model::factory()->create();

    expect(Str::isUlid($record->id))->toBeTrue()
        ->and($record->getIncrementing())->toBeFalse();
})->with([User::class, Itp::class, UniversalItp::class, Wildcard::class]);

it('round-trips the JSON content columns as arrays', function (string $model) {
    $content = [
        'attunement_timings' => [['start' => 0.5, 'end' => 3.25, 'text' => 'I am here.', 'words' => [['word' => 'I', 'start' => 0.5, 'end' => 0.75]]]],
        'breath11_lines' => [['order' => 1, 'text' => 'Breathe in.'], ['order' => 2, 'text' => 'Breathe out.']],
        'breath11_timings' => [['start' => 0, 'end' => 2, 'text' => 'Breathe in.', 'words' => []]],
        'quantum_whys' => ['Why is this so easy?', 'Why do I feel so safe?'],
    ];

    $record = $model::factory()->create($content)->fresh();

    expect($record->only(array_keys($content)))->toEqual($content);
})->with([Itp::class, UniversalItp::class]);

it('casts the universal ITP kind to its enum', function () {
    expect(UniversalItp::factory()->create()->fresh()->kind)->toBe(UniversalItpKind::Universal)
        ->and(UniversalItp::factory()->base()->create()->fresh()->kind)->toBe(UniversalItpKind::Base);
});

it('displays the short name, falling back to the title', function (?string $shortName, string $expected) {
    $universal = UniversalItp::factory()->create(['title' => 'Awaken the Wonder Within', 'short_name' => $shortName]);

    expect($universal->fresh()->display_name)->toBe($expected);
})->with([
    'short name set' => ['Awaken Wonder', 'Awaken Wonder'],
    'no short name' => [null, 'Awaken the Wonder Within'],
    'blank short name' => ['', 'Awaken the Wonder Within'],
]);

it('scopes universal ITPs to the preview', function () {
    $preview = UniversalItp::factory()->preview()->create();
    UniversalItp::factory()->create();

    expect(UniversalItp::query()->preview()->pluck('id')->all())->toBe([$preview->id])
        ->and($preview->fresh()->is_preview)->toBeTrue();
});

it('relates a user to their ITPs', function () {
    $user = User::factory()->create();
    $itp = Itp::factory()->forUser($user)->create();

    expect($user->itps->pluck('id')->all())->toBe([$itp->id])
        ->and($itp->user->is($user))->toBeTrue();
});

it('enforces the ITP user foreign key', function () {
    Itp::factory()->create(['user_id' => (string) Str::ulid()]);
})->throws(QueryException::class);

it('issues Sanctum tokens to a user with a ULID key', function () {
    $user = User::factory()->create();

    $token = $user->createToken('phone');

    expect($token->accessToken->tokenable_id)->toBe($user->id)
        ->and($user->tokens()->count())->toBe(1);
});

it('stamps a sync number on every server-side write', function () {
    $itp = Itp::factory()->create();
    $created = $itp->fresh()->sync_seq;

    $itp->update(['title' => 'Renamed']);

    expect($created)->toBeGreaterThan($itp->user->fresh()->sync_seq)
        ->and($itp->fresh()->sync_seq)->toBeGreaterThan($created);
});

it('stamps a sync number when a universal ITP\'s short name or preview flag changes', function () {
    $universal = UniversalItp::factory()->create();
    $created = $universal->fresh()->sync_seq;

    $universal->update(['short_name' => 'Awaken Wonder', 'is_preview' => true]);

    expect($universal->fresh())
        ->sync_seq->toBeGreaterThan($created)
        ->short_name->toBe('Awaken Wonder')
        ->is_preview->toBeTrue();
});

it('never marks a universal ITP dirty on the device', function () {
    $this->useClientRole();

    $universal = UniversalItp::factory()->preview()->create();
    $universal->update(['short_name' => 'Awaken Wonder', 'is_preview' => false]);

    expect($universal->fresh())
        ->sync_dirty->toBeFalse()
        ->sync_rev->toBe(0)
        ->sync_seq->toBeNull();
});

it('never marks a pull-only ITP dirty on the device', function () {
    $this->useClientRole();

    $itp = Itp::factory()->create();
    $itp->update(['title' => 'Renamed']);

    expect($itp->fresh())
        ->sync_dirty->toBeFalse()
        ->sync_rev->toBe(0)
        ->sync_seq->toBeNull();
});
