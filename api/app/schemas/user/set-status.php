<?php

use Models\Platform\User;

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
        'status' => [
            'type' => 'string',
            'required' => false,
            'member_of' => [User::STATUS_ACTIVE, User::STATUS_DISABLED]
        ]
    ]
];