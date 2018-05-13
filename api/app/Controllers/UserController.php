<?php

namespace Controllers;

use Models\Platform\User;
use Models\Platform\AuthEmail;
use Models\Platform\AuthStore;
use Models\Platform\UserGroup;
use Wave\Http\Exception\BadRequestException;
use Wave\Validator\Exception\InvalidInputException;
use Wave\Validator\Result;

/**
 * Class UserController
 * @package Controllers
 *
 * ~BaseRoute /api/users
 * ~RespondsWith json
 */
class UserController extends BaseController {

    /**
     * ~Route GET, <int>user_id
     * ~Validate user/get
     *
     * @Response \Models\Auth\User
     */
    public function get(User $user){
        return $this->respond($user->getCompiledUser());
    }

    /**
     * ~Route GET, get-by-email/<string>email
     * ~Validate user/get-by-email
     *
     * @Response \Models\Auth\User
     */
    public function getByEmailAddress($email){
        return $this->respond(User::loadByEmail($email));
    }

    /**
     * ~Route POST, <int>user_id/set-status
     * ~Validate user/set-status
     *
     * @Response \Models\Auth\User
     */
    public function setStatus(User $user, $status){
        $user->setStatus($status);
        $user->save();

        return $this->respond($user);
    }

    /**
     * ~Route POST, <int>user_id/set-password
     * ~Validate user/set-password
     */
    public function setPassword(User $user){
        return $this->respond($user->addCredentials(AuthEmail::AUTH_TYPE, $this->_cleaned));
    }

    /**
     * ~Route POST, create
     * ~Validate user/create
     *
     * @Response \Models\Auth\User
     */
    public function create(){

        $user = User::create($this->_cleaned);
        return $this->respond($user);
    }

    /**
     * ~Route POST, <int>user_id
     * ~Validate user/update
     *
     * @Response \Models\Auth\User
     */
    public function update(User $user){
        $user->update($this->_cleaned);
        return $this->respond($user);
    }

    /**
     * ~Route POST, <int>user_id/validate-password
     * ~Validate user/validate-password
     */
    public function validatePassword(User $user, $password){
        return $this->respond(['result' => $user->validatePassword($password)]);
    }

    /**
     * ~Route POST, <int>user_id/disable
     * ~Validate user/get
     */
    public function disable(User $user){
        return $this->respond($user->disable());
    }

}