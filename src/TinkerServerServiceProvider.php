<?php

namespace RedMoon\TinkerServer;

use Illuminate\Support\ServiceProvider;
use RedMoon\TinkerServer\Console\TinkerServerCommand;

class TinkerServerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('tinker-server.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                TinkerServerCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'tinker-server');
    }
}
