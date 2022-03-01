<?php

namespace Fatihirday\Suffixed;

use Illuminate\Support\ServiceProvider;

class SuffixServiceProvier extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/suffixed.php', 'suffixed');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Migrations/create_suffixes_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_suffixes_table.php'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/config/suffixed.php' => config_path('suffixed.php'),
        ], 'config');

        // Command
        if ($this->app->runningInConsole()) {
            $this->commands([
               Console\MakeMigrateSuffixCommand::class,
            ]);
        }
    }
}
