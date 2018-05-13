<?php

return [
    'aliases' => [
        'user' => 'user_id'
    ],
    'fields' => [
        'user' => [
            'type' => 'int',
            'required' => true,
            'exists' => [
                'model' => '\\Models\Platform\User',
                'property' => 'user_id'
            ]
        ],
        'password' => [
            'type' => 'string',
            'required' => true
        ]
    ]
];