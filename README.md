Whoops to gitlab
======

This package gives your users the option to create an issue on gitlab upon "Whoops" error when APP_DEBUG is set to false.

## Installation
```
composer require dalnix/whoops-to-gitlab
```
###### If you want to edit views or config file
```
php artisan vendor:publish --provider="Dalnix\WhoopsToGitLab\WhoopsToGitlabServiceProvider"
```
##### Add following to your env file
```
GITLAB_PROJECT_URL=( your gitlab project url : https://gitlab.domain.com/api/v4/projects/(project-ID)/ )
GITLAB_TOKEN=( Personal gitlab token )

SELECTED_BIN=(null or pastebin)
PASTEBIN_API_DEV_KEY=( your api key )
PASTEBIN_API_PASTE_PRIVATE=( 1 or 0  defailt 1)
PASTEBIN_PASTE_EXPIRE_DATE=( default 10M )
PASTEBIN_PASTE_EXPIRE_FORMAT=(default json )
```
### This is still in development but works
