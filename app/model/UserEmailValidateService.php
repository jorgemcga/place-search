<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use App\Model\LogService;
use App\Model\Repository\UserRepository;
use App\Model\UserService;
use App\Model\EmailService;
use Lib\UrlTools;

class UserEmailValidateService
{

    protected $errorMessage = '';
    protected $logService;
    protected $userService;
    protected $emailService;
    protected $token;
    protected $email;
    protected $urlTools;
    protected $userRepository;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->userService = new UserService();
        $this->emailService = new EmailService();
        $this->urlTools = new UrlTools();
        $this->userRepository = $this->userService->getRepository();
    }

    public function send()
    {
        //echo "<br/>Email: " . $this->email . "<br/>";

        if ($this->userService->checkIfEmailExist($this->email) === false) {
            $this->errorMessage = "O e-mail {$this->email} não está cadastrado";
            return false;
        }

        $this->getUserTokenFromDatabase();

        $this->emailService->setTo($this->email);
        $this->emailService->setSubject("Validação de E-mail");
        $this->emailService->setMessage($this->makeMessage());
        
        if (!$this->emailService->send()){
            $this->errorMessage = "Não foi possível enviar o e-mail: " . $this->emailService->getErrorInfo();
            return false;
        }
        
        return true;
    }

    public function validateEmail($email)
    {
        $data = $this->userRepository->getByEmail($email);
        $data['email_valid'] = '1';
        
        $where = array("email" => $email);
        return $this->userRepository->update($data, $where);
    }
    
    public function checkIfEmailValidated($id)
    {
        $user = $this->userRepository->getOne($id);
        return !isset($user['email_valid']) || $user['email_valid'] == 0 ? false : true;
    }
    
    private function getUserTokenFromDatabase()
    {
        $this->token = $this->userService->getToken($this->email);
    }

    private function makeMessage()
    {
        return "<p>"
                . "Para sua segurança seu e-mail deve ser validado, clique no link a seguir para realizar essa operação. <br>"
                . "<a href='" . $this->emailValidateMakeLink() . "'>" . $this->emailValidateMakeLink() . "</a> <br>"
                . "</p>";
    }

    private function emailValidateMakeLink()
    {

        $emailAscii = $this->urlTools->encode($this->email);
        return BASE_URL . '/userEmailValidate/validate/email/' . $emailAscii;
    }
    
    /*
     * Getters and setters
     */

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getRepository()
    {
        return new UserRepository();
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

}
