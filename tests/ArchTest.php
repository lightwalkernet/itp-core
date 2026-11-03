<?php

use Symfony\Component\Finder\Finder;

/*
 * platform.md §3: itp-core ships on every phone. Nothing in it may reach generation, AI or
 * voice code, server-only code, a host app, Horizon or Livewire.
 */
$serverOnly = ['Laravel\Ai', 'Prism', 'ElevenLabs', 'Itp\Server', 'App', 'Laravel\Horizon', 'Livewire'];

arch('itp-core stays device-safe')
    ->expect('Itp\Core')
    ->not->toUse($serverOnly);

/*
 * `toUse()` only matches classes that exist, and none of these packages is installed here,
 * so the arch rule above cannot fail in this repo. This reads every name in the shipped code.
 */
it('names no server-only namespace in shipped code', function () use ($serverOnly) {
    $files = Finder::create()->files()->name('*.php')->in([__DIR__.'/../src', __DIR__.'/../database']);

    $references = [];

    foreach ($files as $file) {
        foreach (PhpToken::tokenize($file->getContents()) as $token) {
            if (! $token->is([T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED])) {
                continue;
            }

            $name = ltrim($token->text, '\\');

            foreach ($serverOnly as $namespace) {
                if (str_starts_with($name, $namespace.'\\')) {
                    $references[] = $file->getRelativePathname().': '.$name;
                }
            }
        }
    }

    expect($references)->toBe([]);
});
