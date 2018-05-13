<?php

namespace Helpers;

use Raven_Client;
use Raven_ErrorHandler;
use Wave\Core;

class Sentry {

    /** @var Raven_Client */
    protected static $instance;

    public static function init($dsn){

        $client = new Raven_Client($dsn, [
            'curl_method' => 'async',
            'processorOptions' => [
                'Raven_SanitizeDataProcessor' => [
                    'fields_re' => '/(token|refresh_token|password|previous_password|new_password)/i',
                    'values_re' => '/^(?:\d[ -]*?){15,16}$/'
                ]
            ]
        ]);

        $client->setSendCallback(function($data) {
            $ignore_types = array('HellPizza\RPC\Exception\MessageExpiredException');

            if (isset($data['exception']) && in_array($data['exception']['values'][0]['type'], $ignore_types))
            {
                return false;
            }

        });

        self::$instance = $client;

        $error_handler = new Raven_ErrorHandler(self::$instance);
        $error_handler->registerExceptionHandler();
        $error_handler->registerErrorHandler();
        $error_handler->registerShutdownFunction();

        return self::$instance;

    }

    public static function getInstance(){
        return self::$instance;
    }

    public static function setUserContext($context = array()){
        if(self::$instance instanceof Raven_Client)
            self::$instance->user_context($context);
    }

    public static function setTagsContext($context = array()){
        if(self::$instance instanceof Raven_Client)
            self::$instance->tags_context($context);
    }

    public static function setExtraContext($context = array()){
        if(self::$instance instanceof Raven_Client)
            self::$instance->extra_context($context);
    }


}