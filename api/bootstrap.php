<?php

$__started = microtime(true);

use Helpers\Sentry;
use \Wave\Auth;
use \Wave\Config;
use \Wave\Exception;
use \Wave\Log;
use \Wave\Core;
use \Wave\Validator;
use \Wave\Router\Action;


define ('DS', '/');
define ('SYS_ROOT', dirname(__FILE__) . DS);
define ('APP_ROOT', SYS_ROOT . 'app' . DS);

set_include_path(SYS_ROOT . PATH_SEPARATOR . get_include_path());
chdir(SYS_ROOT);


date_default_timezone_set('UTC');

$loader = require dirname(__FILE__) . '/vendor/autoload.php';


$conman = function($resolve){
    $config = json_decode(file_get_contents('conman.json'), true);
    return $config[$resolve];
};


Config::setResolver($conman);

Application::bootstrap(array(
    'debug' => array('start_time' => $__started)
));

Validator::useExceptions(true);

return $loader;