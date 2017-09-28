<?php namespace Dalnix\WhoopsToGitlab;


use Dalnix\WhoopsToGitlab\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;


class WhoopsToGitlabServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();"laravel": {
      "providers": [
        "Dalnix\\WhoopsToGitlab\\WhoopsToGitlabServiceProvider"
      ]
    }
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config/gitlab.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('gitlab.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('gitlab');
        }

        $this->mergeConfigFrom($source, 'gitlab');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadViewsFrom(__DIR__.'/views', 'whoopsToGitlab');
        $this->publishes(
            [__DIR__ . '/views' => base_path('resources/views/vendor/whoops-to-gitlab')],
            'views'
        );


        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
        $this->app->bind(
            'gitlab',
            'Dalnix\WhoopsToGitlab\Gitlab'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }


}
