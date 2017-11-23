<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Providers;

use Illuminate\Support\ServiceProvider;

class RevisionableUpgradeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/revisionable-upgrade.php', 'revisionable-upgrade');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/revisionable-upgrade.php' => config_path('revisionable-upgrade.php', 'config'),
        ]);
    }
}
