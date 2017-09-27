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
            'api_dev_key' => env('PASTEBIN_API_DEV_KEY', null),
            'api_paste_private' =>  env('PASTEBIN_API_PASTE_PRIVATE', 1),
            'api_paste_expire_date' => env('PASTEBIN_PASTE_EXPIRE_DATE', '10M'),
            'api_paste_format' => env('PASTEBIN_PASTE_EXPIRE_FORMAT', 'json')
        ]
    ]
];