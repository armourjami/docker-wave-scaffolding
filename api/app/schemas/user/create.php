<?php

use Helpers\PasswordHelper;
use Models\Platform\AuthEmail;

return [
    'fields' => [
        'first_name' => [
            'type' => 'string',
            'required' => true,
            'max_length' => 45
        ],
        'last_name' => [
            'type' => 'string',
            'required' => true,
            'max_length' => 45
        ],
        'email' => [
            'type' => 'email',
            'required' => true,
            'max_length' => 45,
            'unique' => [
                'model' => '\\Models\Platform\User',
                'property' => 'email'
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