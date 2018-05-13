<?php

return [
    'fields' => [
        'customer_id' => [
            'type' => 'int',
            'required' => true
        ],
        'token' => [
            'type' => 'string',
            'required' => true
        ],
        'new_password' => [
            'type' => 'string',
            'required' => true,
            'min_length' => 6
        ]
    ]
];