<?php

namespace Itp\Core\Tests;

use Itp\Core\ItpCoreServiceProvider;
use Lightwalker\Sync\SyncServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use PDO;

/**
 * The package booted as `itp-server` boots it: sync in the server role, the sync server
 * migrations and itp-core's migrations loaded explicitly. Each test migrates up, and rolls
 * back on teardown, so every `down()` runs too.
 *
 * In-memory SQLite by default. `ITP_CORE_TEST_DB=mysql` runs the suite on Herd MySQL instead,
 * in an `itp_core_test` database that is recreated once per run.
 */
abstract class TestCase extends Orchestra
{
    protected static bool $mysqlDatabaseCreated = false;

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [SyncServiceProvider::class, ItpCoreServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        if (static::usesMysql()) {
            $app['config']->set('database.connections.mysql', [
                ...$app['config']->get('database.connections.mysql'),
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'itp_core_test',
                'username' => 'root',
                'password' => '',
            ]);

            $app['config']->set('database.default', 'mysql');

            static::createMysqlDatabase();

            return;
        }

        $app['config']->set('database.default', 'testing');

        // Testbench leaves these off; Laravel's sqlite connection, and so the device, turns them on.
        $app['config']->set('database.connections.testing.foreign_key_constraints', true);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(SyncServiceProvider::serverMigrationsPath());
        $this->loadMigrationsFrom(__DIR__.'/Fixtures/migrations');
        $this->loadMigrationsFrom(ItpCoreServiceProvider::migrationsPath());
    }

    protected function useClientRole(): void
    {
        config(['sync.role' => 'client']);
    }

    public static function usesMysql(): bool
    {
        return getenv('ITP_CORE_TEST_DB') === 'mysql';
    }

    /**
     * Drop and recreate the test database once per run, so a crashed run never leaves tables behind.
     */
    protected static function createMysqlDatabase(): void
    {
        if (static::$mysqlDatabaseCreated) {
            return;
        }

        $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
        $pdo->exec('DROP DATABASE IF EXISTS itp_core_test');
        $pdo->exec('CREATE DATABASE itp_core_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        static::$mysqlDatabaseCreated = true;
    }
}
