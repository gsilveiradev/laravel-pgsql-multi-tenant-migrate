<?php

namespace Guissilveira\Laravel\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MultiTenantMigrateServiceProvider extends ServiceProvider
{
    /**
     * After register is called on all service providers, then boot is called
     */
    public function boot()
    {
        //
    }

    /**
     * Register is called on all service providers first.
     *
     * We must register the extension before anything tries to use the mailing functionality.
     * None of the closures are executed until someone tries to send an email.
     *
     * This will register a closure which will be run when 'swift.transport' (the transport manager) is first resolved.
     * Then we extend the transport manager, by adding the Maildocker transport object as the 'maildocker' driver.
     */
    public function register()
    {
        $this->app->singleton('command.guissilveira.tenant', function ($app) {
            return $app['Guissilveira\Laravel\Commands\TenantMigrationCommand'];
        });

        $this->commands('command.guissilveira.tenant');
    }
}
