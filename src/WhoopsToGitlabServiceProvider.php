<?php namespace Dalnix\WhoopsToGitLab;


use Dalnix\WhoopsToGitlab\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;
use Gitlab\Client;
use Vinkla\GitLab\Facades\GitLab;
use Vinkla\GitLab\GitLabServiceProvider;

/**
 * This is the GitLab service provider class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class WhoopsToGitlabServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
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

        require __DIR__ . '/../vendor/autoload.php';

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(GitLabServiceProvider::class);
        $this->app->bind('GitLab', function () { return new GitLab();});
    }


}
