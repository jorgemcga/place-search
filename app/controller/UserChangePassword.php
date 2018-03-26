<?php

namespace App\Controller;

use App\Model\MarkerTypeService;
use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\UserEmailValidateService;
use Lib\UrlTools;
use App\Model\UserChangePasswordService;

class UserChangePassword extends AppController
{

    private $userService;
    private $userEmailService;
    private $urlTools;
    private $userChangePasswordService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userEmailService = new UserEmailValidateService();
        $this->urlTools = new UrlTools();
        $this->userChangePasswordService = new UserChangePasswordService();
    }

    public function passwordSetAction()
    {

        $data['token'] = Sanitize::sanitizeString($this->getParam('token', ''));
        return array("view" => "user-change-password/password-set", "data" => $data, "layout" => 'site');
    }

    public function writeAction()
    {
        $password = Sanitize::sanitizeString(filter_input(INPUT_POST, 'password'));
        $password2 = Sanitize::sanitizeString(filter_input(INPUT_POST, 'password2'));
        $token = Sanitize::sanitizeString(filter_input(INPUT_POST, 'token'));

        if ($this->userChangePasswordService->validate($password, $password2) === false) {
            FlashMsg::setMsgError($this->userChangePasswordService->getErrorMessage());
            $data['token'] = $token;
            return array("view" => "user-change-password/password-set", "data" => $data, "layout" => 'site');
        }

        if ($this->userChangePasswordService->write($token, $password) === false) {
            FlashMsg::setMsgError($this->userChangePasswordService->getErrorMessage());
            $data['token'] = $token;
            return array("view" => "user-change-password/password-set", "data" => $data, "layout" => 'site');
        }

        FlashMsg::setMsgOk("Sua senha foi definida com sucesso! Faça login para Entrar.");
        return Redirect::to(BASE_URL);
    }
}
