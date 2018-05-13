<?php

use Helpers\Sentry;
use Wave\Hook;
use Wave\Http\Request;
use Wave\Router;

include_once('../bootstrap.php');

if(\Wave\Config::get('deploy')->ssl === true){
    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on'){
        \Wave\Utils::redirect('https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == "OPTIONS") {

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Content-Length, Token, Accept');

    header('Access-Control-Max-Age: 1728000');
    header("Content-Length: 0");
    header("Content-Type: text/plain");
    exit(0);
}

header('Access-Control-Allow-Origin: *');

$request = Request::createFromGlobals();

Application::authByToken($request);
Wave\Validator::nullCleaned(false);

// Setting New Relic app name for monitoring
if (extension_loaded('newrelic')) {
    newrelic_set_appname('SCAFFOLD');

    // Register the Controller and Action with NR so we get some visibility into what's happening
    Hook::registerHandler('router.before_invoke', function(\Wave\Router\Action $a){
        list($controller_class, $action_method) = explode('.', $a->getAction(), 2) + array(null, null);
        newrelic_name_transaction(sprintf('%s:%s', ltrim($controller_class, '/'), $action_method));
    });

    /** @var \Helpers\Session $auth */
    $auth = $request->getAuthorization();

    $source_ip = $request->getHeaders()->get('X-Forwarded-For', '');
    if(strlen($source_ip) > 0 && strpos($source_ip, ',') !== false){
        $source_ip = explode(',', $source_ip)[0];
    }

    newrelic_add_custom_parameter('source_ip', $source_ip);
    newrelic_add_custom_parameter('customer_id', $auth instanceof \Helpers\Session ? $auth->customer_id : '');

}

Sentry::setExtraContext($request->parameters->all());

$response = Router::init('default')->route($request);
$response->send();