<?php

namespace Controllers;

use HellPizza\RPC\Exception\InvalidInputException;
use Wave\Config;
use Wave\Controller;
use Wave\Core;
use Wave\Exception;
use Wave\Http\Exception\UnauthorizedException;
use Wave\Http\Response\HtmlResponse;
use Wave\Http\Response\RedirectResponse;
use Wave\Http\Response\TextResponse;
use Wave\Log;
use Wave\Http\Response;
use Wave\Utils;

class ExceptionController extends Controller {

    protected $exception;
    protected $error;

    private static $methods = array(
        'html', 'json', 'dialog', 'cli'
    );

    public function execute(){

        $this->_response_method = Exception::getResponseMethod();
        if(!in_array($this->_response_method, self::$methods))
            $this->_response_method = 'json';

        $this->exception = $this->_data['exception'];

        $this->error['code'] = $this->_data['exception']->getCode();
        $this->error['message'] = $this->_data['exception']->getMessage();

        $this->_status = $this->_data['exception']->getCode();
        if(!ctype_digit($this->_status) || $this->_status <= 0 || $this->_status >= 600)
            $this->_status = Response::STATUS_SERVER_ERROR;

        return $this->respond();

    }

    protected function respondJSON($payload = null){
        if(Core::$_MODE == Core::MODE_DEVELOPMENT)
            $this->backtrace = $this->_data['exception']->getTraceAsString();

        if($this->exception instanceof InvalidInputException){
            $payload = ['errors' => $this->exception->getViolations()];
        }

        unset($this->exception, $payload['exception']);

        return parent::respondJSON($payload);
    }


    protected function respondHtml(){
        if($this->exception instanceof UnauthorizedException){
            $to = urlencode($this->_request->getPathAndQueryString());
            return new RedirectResponse('/sign-in?to='.$to);
        }
        else {
            ob_start();
            $exception = $this->_data['exception'];
            include_once(Config::get('wave')->path->views . 'system' . DS . 'exception.php');
            $html = ob_get_contents();
            ob_end_clean();

            return new HtmlResponse($html, $this->_status);
        }
    }

    protected function respondDialog(){
        ob_start();
        $exception = $this->_data['exception'];
        include_once Config::get('wave')->path->views . 'system' . DS . 'exception-dialog.php';

        $html = ob_get_contents();
        ob_end_clean();

        return $this->respondJSON(array('html' => $html));
    }


    protected function respondCLI(){

        $message = "\n\n***** ERROR ****** \n"
            . "  Code:    ".$this->_data['exception']->getCode()."\n"
            . "  Message: ".$this->_data['exception']->getMessage()."\n"
            . "**************\n\n";

        return new TextResponse($message);

    }

}




?>