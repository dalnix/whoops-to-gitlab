<?php namespace Dalnix\WhoopsToGitLab;

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
        $source = realpath(__DIR__.'/config/whoops-to-gitlab.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('whoops-to-gitlab.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('whoops-to-gitlab');
        }
        $this->mergeConfigFrom($source, 'gitlab');  
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
