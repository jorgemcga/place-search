<?php

namespace App\Controller;

use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\UserEmailValidateService;
use Lib\UrlTools;
use App\Model\UserResetPasswordService;

class UserResetPassword extends AppController
{

    private $userService;
    private $userEmailService;
    private $urlTools;
    private $userResetPassword;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userEmailService = new UserEmailValidateService();
        $this->urlTools = new UrlTools();
        $this->userResetPassword = new UserResetPasswordService();
    }

    public function formAction()
    {
        return array("view" => "user-reset-password/form", "data" => [], "layout" => 'site-clean');
    }

    public function requestAction()
    {
        $email = Sanitize::sanitizeString(filter_input(INPUT_POST, 'email'));
        
        if ($this->userResetPassword->setEmail($email)->request() === false) {
            FlashMsg::setMsgError($this->userResetPassword->getErrorMessage());
            return Redirect::to(BASE_URL . 'userResetPassword/form');
        }

        return array("view" => "user-reset-password/request-success", "data" => [], "layout" => 'site');
    }
}
