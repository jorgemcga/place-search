<?php

namespace App\Model;

use App\Model\UserService;
use App\Model\SessionService;
use App\Model\LogService;
use Edily\Base\Redirect;
use \Exception as Exception;
use Lib\HashTools;

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class AuthService
{
    protected $errorMessage = '';
    protected $userService;
    protected $sessionService;
    protected $userData;
    protected $logService;
    protected $hashService;
    protected $urlDeniedAccess = 'auth/login';

    public function __construct()
    {
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        $this->sessionService = new SessionService();
        $this->logService = new LogService();
        $this->hashService = new HashTools();
    }

    public function login($email, $password)
    {
        $passwordHash = $this->hashService->setPassword($password)->encrypt();
        $userData = $this->userRepository->getByEmailPassword($email, $passwordHash);
        if($userData === false){
            $this->errorMessage = "Usuário e/ou senha inválido(s)";
            return false;
        }
        $this->setUserData($userData);
        return true;
    }

    public function clearUserData()
    {
        $this->setUserData(false);
    }
    
    public function authValidade($type = [])
    {
        $userLogged = $this->sessionService->get('authenticated_user');
        
        if(!isset($userLogged['id']) || array_search($userLogged['type'], $type) === false){
            
            return false;
            
//            if(!empty($this->urlDeniedAccess)){
//                return Redirect::to($this->urlDeniedAccess);
//            }
//            die("Acesso restrito");
        }
        
        return true;
    }
    
    public function getUserData()
    {
        return $this->sessionService->get('authenticated_user');
    }

    public function setUserData($userData)
    {
        return $this->sessionService->set('authenticated_user', $userData);
    }
    
    public function logout()
    {
        return $this->clearUserData();
    }
}
