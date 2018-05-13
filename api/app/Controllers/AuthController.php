<?php

namespace Controllers;

use DateTime;
use Exceptions\UnauthorizedException;
use Models\Platform\AccessToken;
use Models\Platform\AuthAttempt;
use Models\Platform\AuthStore;
use Models\Platform\AuthDevice;
use Models\Platform\AuthEmail;
use Models\Platform\User;
use Models\Platform\RefreshToken;
use Wave\Config;
use Wave\Http\Exception\BadRequestException;

/**
 * Class AuthController
 * @package Controllers
 *
 * ~BaseRoute /api/auth
 */
class AuthController extends BaseController {

    /**
     * ~Route POST, authenticate/email
     * ~Validate auth/authenticate-email
     */
    public function authByEmail($email_address, $password){

        $user = AuthEmail::authenticate($email_address, $password, $this->getSourceIpAddress());
        if(!$user instanceof User || $user->status !== User::STATUS_ACTIVE)
            throw new BadRequestException('Incorrect credentials, please check your entry and try again.');
        $tokens = $this->createTokenPair($user);

        return $this->respond($tokens);

    }


    /**
     * ~Route POST, refresh
     * ~Validate auth/refresh
     */
    public function refreshAccessToken($refresh_token){

        /** @var RefreshToken $token */
        $token = RefreshToken::load_valid_by_token($refresh_token);
        if(!$token instanceof RefreshToken)
            throw new UnauthorizedException('This refresh token is invalid, please re-authenticate.');

        $tokens = $this->createTokenPair($token->User, $token->Client, $token->additional_data);

        return $this->respond($tokens);

    }

    /**
     * ~Route POST, /access-token/<string>access_token
     * ~Validate auth/get
     */
    public function getAccessToken($access_token){

        /** @var AccessToken $token */
        $token = AccessToken::load_valid_by_token($access_token);
        if(!$token instanceof AccessToken)
            throw new UnauthorizedException('This token is invalid, please re-authenticate.');

        return $this->respond($token->getCompiledToken());

    }

    /**
     * ~Route POST, logout
     * ~Validate auth/logout
     */
    public function logout($user_id){

        RefreshToken::revoke($user_id);
        AccessToken::revoke($user_id);

        return $this->respond(true);

    }

    private function createTokenPair(User $user){

        $access_token = AccessToken::create($user);
        $refresh_token = RefreshToken::create($user);

        $config = Config::get('app')->auth;

        $access_token_validity = (isset($config['access_token_validity']) ? $config['access_token_validity'] : '+2 hours');
        $refresh_token_validity = (isset($config['refresh_token_validity']) ? $config['refresh_token_validity'] : '+24 hours');

        $expiry = new DateTime($access_token_validity);
        $access_ttl = strtotime($expiry->format(DateTime::ATOM)) - strtotime('now');

        $expiry = new DateTime($refresh_token_validity);
        $refresh_ttl = strtotime($expiry->format(DateTime::ATOM)) - strtotime('now');

        return ['access_token' => $access_token, 'refresh_token' => $refresh_token, 'user_id' => $user->user_id, 'name' => $user->full_name, 'expiry' => ['access_token' => $access_ttl, 'refresh_token' => $refresh_ttl]];
    }
}