<?php

namespace Itp\Core;

use Illuminate\Support\ServiceProvider;
use Itp\Core\Models\Itp;
use Itp\Core\Models\UniversalItp;
use Itp\Core\Models\User;
use Itp\Core\Models\Wildcard;

class ItpCoreServiceProvider extends ServiceProvider
{
    /**
     * Register itp-core's sync types ahead of the host's own. A key the host has already set wins.
     */
    public function register(): void
    {
        $this->app['config']->set('sync.models', array_merge(
            static::syncTypes(),
            $this->app['config']->get('sync.models', []),
        ));
    }

    /**
     * itp-core's sync types. Registration order is pull order: `users` comes first, because
     * every user-scoped row references it. Pushes and the sign-out wipe must run in reverse
     * order, so a child row is never written before, or left behind after, its parent.
     *
     * @return array<string, array{model: class-string, scope: 'user'|'global', fields?: list<string>, pull_every?: string, owner_key?: string}>
     */
    public static function syncTypes(): array
    {
        return [
            'users' => ['model' => User::class, 'scope' => 'user', 'fields' => [], 'owner_key' => 'id'],
            'itps' => ['model' => Itp::class, 'scope' => 'user', 'fields' => []],
            'universal_itps' => ['model' => UniversalItp::class, 'scope' => 'global', 'pull_every' => '1 day'],
            'wildcards' => ['model' => Wildcard::class, 'scope' => 'global', 'pull_every' => '1 day'],
        ];
    }

    /**
     * The shared migrations. Never auto-loaded: `itp-server` loads them, and `itp-app` loads them
     * only on the mobile build.
     */
    public static function migrationsPath(): string
    {
        return dirname(__DIR__).'/database/migrations';
    }
}
