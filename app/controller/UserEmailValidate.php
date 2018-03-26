<?php

namespace App\Controller;

use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\UserEmailValidateService;
use Lib\UrlTools;

class UserEmailValidate extends AppController
{

    private $userService;
    private $userEmailService;
    private $urlTools;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userEmailService = new UserEmailValidateService();
        $this->urlTools = new UrlTools();
    }

    public function sendAction($email = null)
    {
        $emailAscii = isset($email) ? $email : $this->getParam('email');
        $email = $this->urlTools->decode($emailAscii);
        if ($this->userEmailService->setEmail($email)->send() === false) {
            FlashMsg::setMsgError($this->userEmailService->getErrorMessage());
            return false;
        }
//        FlashMsg::setMsgOk("Um e-mail foi enviado"$data['email'] = $email;
        return true;
    }

    public function validateAction()
    {
        $emailAscii = $this->getParam('email');
        $email = $this->urlTools->decode($emailAscii);
        $data['email'] = $email;
        if ($this->userEmailService->validateEmail($email)) {
            return array("view" => "user-email-validate/success", "data" => $data, "layout" => 'site');
        }
        return array("view" => "user-email-validate/error", "data" => $data, "layout" =>  'site');
    }
}
