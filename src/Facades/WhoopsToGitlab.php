<?php namespace Dalnix\WhoopsToGitlab\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the WhoopsToGitlab facade class.
 *
 * @author Christian Janbjer <christian@dalnix.se>
 */
class WhoopsToGitlab extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'whoopstogitlab';
    }
}
