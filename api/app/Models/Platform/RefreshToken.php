<?php

/**
* Model stub class generated by the Wave\DB ORM.
* Changes made to this file WILL NOT BE OVERWRITTEN when database is next generated.
*
* @package:   Models\Models\Auth
* @generated: 2015-09-28 08:02:32
*
* @schema:    service-auth
* @table:     refresh_token
* @engine:    InnoDB
* @collation: utf8_general_ci
*
*/

namespace Models\Platform;

use DateTime;
use Helpers\RandomStringHelper;
use Wave,
    Wave\DB;
use Wave\Config;

class RefreshToken extends Base\RefreshToken {

    /**
     * @param User $user
     * @param Client $client
     * @return bool|string
     *
     * Creates a refresh token for a combination of user and client
     */
    public static function create(User $user){

        $config = Config::get('app')->auth;

        $validity = (isset($config['refresh_token_validity']) ? $config['refresh_token_validity'] : '+24 hours');

        $params['user_id'] = $user->user_id;
        $params['issued'] = new DateTime();
        $params['expires'] = new DateTime($validity);
        $params['revoked'] = false;
        $params['refresh_token'] = RandomStringHelper::getString();

        $token = new RefreshToken();
        $token->updateFromArray($params);

        if($token->save())
            return $token->refresh_token;

        return false;

    }

    /**
     * @param $refresh_token
     * @return array|null|DB\Model
     * @throws DB\Exception
     * @throws Wave\Exception
     *
     * Loads any valid (not revoked, not expired) token that matches the refresh token
     *
     */
    public static function load_valid_by_token($refresh_token) {

        $now = new DateTime();

        return DB::get()->from('RefreshToken')
                  ->where('refresh_token = ?', $refresh_token)
                   ->and('revoked = ?', false)
                   ->and('expires > ? ', $now)
                 ->fetchRow();

    }

    public function getadditional_data()
    {
        $string = parent::getadditional_data();

        if($string !== null && $string !== '') {
            $data = json_decode($string, true);

            if(json_last_error() == JSON_ERROR_NONE)
                return $data;
        }

        return [];
    }

    /**
     * @return array|mixed
     *
     * Set additional data that will be re-set on any new
     * access tokens created by this refresh token
     *
     */
    public function setadditional_data($data = array())
    {

        if(is_array($data) === false){
            $data = [];
        }

        $string = json_encode($data);

        return parent::setadditional_data($string);
    }

    /**
     *
     * Revokes an active token - making it useless.
     *
     */
    public static function revoke($user_id) {

        $now = new DateTime();

        $tokens = DB::get()->from('RefreshToken')
                            ->where('user_id = ?', $user_id)
                            ->where('revoked = ?', false)
                            ->where('expires > ?', $now)
                            ->fetchAll();

        /** @var RefreshToken $token */
        foreach ($tokens as $token){
            $token->revoked = true;
            $token->save();
        }

    }

}