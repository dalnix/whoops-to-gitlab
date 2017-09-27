<?php

return [
    'connection' => [
        'token' => env('GITLAB_TOKEN', null),
        'url' => env('GITLAB_PROJECT_URL', null),
        'project' => env('GITLAB_PROJECT_ID', null),
    ],
    'selected_bin' => 'pastebin',
    'bins' => [
        'pastebin' => [
            'api_dev_key' => '17ced80b81162adcebdc6674306f5970',
            'api_paste_private' => '1',
            'api_paste_expire_date' => '10M',
            'api_paste_format' => 'json'
        ],
        'dalnix' => [
            'url' => 'https://privatebin.dalnix.se/'
        ]
    ]
];