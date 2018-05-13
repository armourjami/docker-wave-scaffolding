<?php

namespace Helpers;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use HellPizza\StyxSDK\Client\Auth\PermissionEntitySet;
use HellPizza\StyxSDK\Client\Cache\DataCache;
use HellPizza\StyxSDK\Client\Cache\MemCache;
use HellPizza\StyxSDK\Client\Auth\Permissions;
use HellPizza\StyxSDK\Client\Consumer\Auth\User;
use Wave\Config;
use Wave\Http\Request;
use Wave\Http\Request\AuthorizationAware;
use Wave\Log;

class Session implements AuthorizationAware {

    const CLIENT_ID = 'api';

    public $customer_id;

    private function __construct($customer_id) {
        $this->customer_id = $customer_id;
    }

    public static function validate($token) {

        $config = Config::get('app')->customer_auth->jwt;
        $required_claims = ['customer_id', 'client_id', 'expires', 'token'];

        try {
            $token = JWT::decode($token, $config->key, [$config->algorithm]);
        } catch (Exception $e) {
            Log::write('auth', 'Authentication token decoding failed', Log::INFO);
            return false;
        }

        if(!is_object($token)){
            Log::write('auth', 'Malformed access token provided', Log::INFO);
            return false;
        }

        foreach($required_claims as $claim){
            if(!property_exists($token, $claim)){
                Log::write('auth', "Required claim {$claim} missing from access token", Log::INFO);
                return false;
            }
        }

        if($token->client_id != self::CLIENT_ID){
            Log::write('auth', 'Access token client ID mismatch', Log::WARNING);
            return false;
        }

        $now = new DateTime();
        $expiry = $token->expires;
        if($expiry < $now->getTimestamp()){
            Log::write('auth', 'Access token has expired, re-authentication required.', Log::INFO);
            return false;
        }

        return new Session($token->customer_id);
    }

    public function hasAuthorization(array $levels, Request $request) {

        list($permission) = $levels + [NULL];

        if ($permission == 'authenticated') {
            return true;
        }

        $customer_id = $request->get('customer_id');
        if(!isset($customer_id) || $customer_id === $this->customer_id)
            return true;

        return false;
    }
}