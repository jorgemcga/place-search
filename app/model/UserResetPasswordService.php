<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use App\Model\LogService;
use App\Model\UserService;
use App\Model\EmailService;
use App\Model\UserChangePasswordService;

class UserResetPasswordService
{

    protected $databaseService;
    protected $errorMessage = '';
    protected $logService;
    protected $userRepository = null;
    protected $userService;
    protected $userChangePassword;
    protected $user;
    protected $email;
    protected $emailService;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        $this->userChangePassword = new UserChangePasswordService();
        $this->emailService = new EmailService();
    }

    public function request()
    {
        if ($this->validate() === false) return false;
        $this->userService->resetToken($this->email);
        $this->user = $this->userRepository->getByEmail($this->email);
        
        $validar = new UserEmailValidateService();
        $retorno = $validar->validateEmail($this->email);

        return $this->makeLink();
        //return $this->sendEmail();
    }

    public function validate()
    {
        if ($this->userService->checkIfEmailExist($this->email) === false) {
            $this->errorMessage = "E-mail não cadastrado";
            return false;
        }
        return true;
    }

    public function sendEmail()
    {
        $this->emailService->setTo($this->email);
        $this->emailService->setSubject("Cadastro PlanMob");
        $this->emailService->setMessage($this->makeMessage());
        
        if (!$this->emailService->send()) {
            $this->errorMessage = "Não foi possível enviar o e-mail: " . $this->emailService->getErrorInfo();
            return false;
        }

        return true;
    }

    private function makeMessage()
    {
        return "<p>"
                . "Uma definição de senha foi solicitada, clique no link a seguir para realizar essa operação. <br>"
                . "<a href='" . $this->makeLink() . "'>" . $this->makeLink() . "</a> <br>"
                . "</p>";
    }

    private function makeLink()
    {
        return $this->userChangePassword->changePasswordRequestLink($this->user['id']);
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
}