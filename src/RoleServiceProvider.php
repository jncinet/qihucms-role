<?php

namespace Qihucms\Role;

use Illuminate\Support\ServiceProvider;
use Qihucms\Role\Commands\CheckExpiresCommand;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckExpiresCommand::class,
            ]);
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'role');

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
