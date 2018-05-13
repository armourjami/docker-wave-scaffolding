<?php

namespace Controllers;


/**
 * ~BaseRoute /first
 */
class FirstController extends BaseController {


    /**
     * ~Route GET, 
     */
    public function test() {
        return $this->respond(['Test' => 'something']);
    }

} 