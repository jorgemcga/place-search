<?php

namespace App\Controller;

use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\AuthService;

class Auth extends AppController
{

    private $userService;
    private $userRepository;
    //private $authService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        //$this->authService = new AuthService();
    }

    public function loginAction()
    {
        $data = array();
        return array("view" => "auth/login", "data" => $data, "layout" => 'site-clean');
    }
    
    public function logoutAction()
    {
        $this->authService->logout();
        return Redirect::to(BASE_URL . '');
    }
    
    public function authAction()
    {
        $email = Sanitize::sanitizeString(filter_input(INPUT_POST, 'email'));
        $password = Sanitize::sanitizeString(filter_input(INPUT_POST, 'password'));
        if($this->authService->login($email, $password) === false){
            FlashMsg::setMsgError("Login e/ou senha invalido(s)");
            return Redirect::to(BASE_URL . '');
        }
        return Redirect::to(BASE_URL . '');
    }
}
