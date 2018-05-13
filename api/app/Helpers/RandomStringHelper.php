<?php

namespace Helpers;

class RandomStringHelper {

    const DEFAULT_LENGTH = 32;

    public static function getString($length = self::DEFAULT_LENGTH){

        return bin2hex(openssl_random_pseudo_bytes($length));

    }


}