<?php

namespace lky_vendor\laravel_command;

use Illuminate\Support\ServiceProvider;
use lky_vendor\laravel_command\Command_Service\Yu_Clear_Log;
use lky_vendor\laravel_command\Command_Service\Yu_Ctrl;
use lky_vendor\laravel_command\Command_Service\Yu_Db;
use lky_vendor\laravel_command\Command_Service\Yu_test;

class laravel_commandServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/lky_command.php' => config_path('lky_command.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(array(
            Yu_Db::class,
            Yu_Clear_Log::class,
            Yu_Ctrl::class,
            Yu_test::class
        ));

        $this->mergeConfigFrom(
            __DIR__.'/config/lky_command.php', 'lky_command'
        );

    }
}
