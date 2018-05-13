<?php


namespace Exceptions;


class ForbiddenException extends APIException {

    public function __construct($message = 'Forbidden', $code = null){
        if($code === null)
            $code = 403;
        return parent::__construct($message, $code);
    }

}