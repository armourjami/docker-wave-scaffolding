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
        'first_name' => [
            'type' => 'string',
            'required' => false,
            'max_length' => 45
        ],
        'last_name' => [
            'type' => 'string',
            'required' => false,
            'max_length' => 45
        ],
        'email' => [
            'type' => 'email',
            'required' => false,
            'max_length' => 45,
            'callable' => function($email, $validator, &$key, &$message){
                $message = 'This email address has already been used by another account';
                if($email != $validator['user']->email){
                    return !(User::loadByEmail($email) instanceof User);
                }

                return true;
            }
        ]
    ]
];