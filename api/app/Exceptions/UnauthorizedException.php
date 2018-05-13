<?php


namespace Exceptions;


class UnauthorizedException extends APIException {

    public function __construct($message = 'Unauthorized', $code = null){
        if($code === null)
            $code = 401;
        return parent::__construct($message, $code);
    }

}