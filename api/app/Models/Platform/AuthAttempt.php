<?php

/**
* Model stub class generated by the Wave\DB ORM.
* Changes made to this file WILL NOT BE OVERWRITTEN when database is next generated.
*
* @package:   Models\Models\Auth
* @generated: 2018-01-30 09:10:42
*
* @schema:    service_auth
* @table:     auth_attempt
* @engine:    InnoDB
* @collation: utf8_general_ci
*
*/

namespace Models\Platform;

use Wave,
    Wave\DB;

class AuthAttempt extends Base\AuthAttempt {

    public static function record($user_id, $client_id, $ip_address = '', $country = '', $successful = false){

        $attempt = new self();
        $attempt->user_id = $user_id;
        $attempt->source_ip = $ip_address;
        $attempt->successful = $successful;

        $attempt->timestamp = new \DateTime();
        $attempt->save();

        return $attempt;

    }

}