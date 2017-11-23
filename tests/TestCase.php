<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests;

use Fico7489\Laravel\RevisionableUpgrade\Providers\RevisionableUpgradeServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/database/migrations/'),
        ]);
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
    
    protected function getPackageProviders($app)
    {
        return [RevisionableUpgradeServiceProvider::class];
    }
}
