<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'API Documentation',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'docs_json' => 'api-docs.json',
                'annotations' => base_path('app'),
            ],
        ],
    ],
];
