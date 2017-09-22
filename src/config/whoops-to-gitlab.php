<?php

return [
    'default' => 'main',
    'connections' => [
        'main' => [
            'token' => env('GITLAB_TOKEN', null),
            'url' =>  env('GITLAB_PROJECT_URL', null),
        ]
    ]
];