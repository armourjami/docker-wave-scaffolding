<?php

use Helpers\PasswordHelper;

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
            'required' => true,
            'max_length' => 72,
            'callable' => function(&$data, &$validator, &$key, &$message){

                $result = PasswordHelper::check_password_complexity($data);
                if(is_array($result)) {
                    $message = $result['errors'];
                    return false;
                }

                return true;

            }
        ]
    ]
];