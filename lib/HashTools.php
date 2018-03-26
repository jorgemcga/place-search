<?php

namespace Lib;

/**
 * Description of Sanitize
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class HashTools
{

    private $password;
    private $passwordTemp;
    private $passwordCrypt;
    private $errorMessage;
    private $salt = "hjb#frhlIgthnvfR$%&hYtQP197J";
    private $noValidade = false;

    public function addSalt($salt)
    {
        $this->salt =  $salt . $this->salt;
        return $this;
    }
    
    private function validate()
    {
        if ($this->noValidade === true){
            return true;
        }
                
        if (strlen($this->password) < 6) {
            $this->errorMessage = "String muito curta: " . $this->password;
            return $this->errorMessage;
        }
    }

    private function completeLen($numPass = 4)
    {
        for ($i = 0; $i <= $numPass; $i++) {
            $this->passwordTemp .= $this->passwordTemp;
        }
        return $this;
    }

    private function splitLen($numChars = 60)
    {
        $this->passwordTemp = substr($this->passwordTemp, 0, $numChars);
        return $this;
    }

    private function mergeHash()
    {
        $passwordTemp = '';
        $len = strlen($this->salt);
        for ($i = 0; $i <= $len; $i++) {
            $passwordTemp .= substr($this->passwordTemp, $i, 1);
            $passwordTemp .= substr($this->salt, $i, 1);
        }
        $this->passwordTemp = $passwordTemp;
        return $this;
    }

    private function passwordHashPhp()
    {
        $this->passwordCrypt = sha1($this->passwordTemp)
                . md5($this->passwordTemp);
        return $this;
    }

    private function revHalf()
    {
        $passwordTemp = $this->passwordTemp;
        $middlePos = strlen($passwordTemp) / 2;
        $part1 = substr($passwordTemp, 0, $middlePos);
        $part2 = substr($passwordTemp, $middlePos, strlen($passwordTemp));
        $this->passwordTemp = strrev($part1) . $part2;
        return $this;
    }

    public function encrypt()
    {
        $this->validate();

        $this->passwordTemp = $this->password;

        return $this->baseOperations()
                        ->passwordHashPhp()
                        ->getPasswordCrypt();
    }

    private function baseOperations()
    {
        $this->completeLen()
                ->splitLen()
                ->mergeHash()
                ->revHalf();
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordCrypt()
    {
        return $this->passwordCrypt;
    }

    public function getPasswordTemp()
    {
        return $this->passwordTemp;
    }

    public function getPersistentDataFromCli()
    {
        $data['userAgent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'NO';
        $data['remoteAddr'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'NO';
        $data['httpCookie'] = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : 'NO';
        return $data;
    }
    
    public function noValidatePassword()
    {
        $this->noValidade = true;
        return $this;
    }

}
