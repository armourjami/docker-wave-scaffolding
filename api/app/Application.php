<?php

use Helpers\Sentry;
use Helpers\Session;
use Wave\Auth;
use Wave\Config;
use Wave\Core;
use Wave\Exception;
use Wave\Http\Request;

class Application extends Core {

    public static function bootstrap(array $config) {
        
        Exception::register('\\Controllers\\ExceptionController');
        
        parent::bootstrap($config);
    }

    public static function authByToken(Request $request) {
        $token = $request->server->get('PHP_AUTH_PW', false);

        if (!empty($token)) {
            $session = Session::validate($token);
            if ($session instanceof Session) {
                $request->setAuthorization($session);
                Auth::registerIdentity($session->user_id);
                Sentry::setUserContext(['user_id' => $session->user_id]);
            }
        }
    }
    
}
