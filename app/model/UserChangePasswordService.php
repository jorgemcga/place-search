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
use Lib\HashTools;
use Edily\Base\Redirect;

class UserChangePasswordService
{

    protected $databaseService;
    protected $errorMessage = '';
    protected $logService;
    protected $userRepository = null;
    protected $userService;
    protected $hashTools;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        $this->hashTools = new HashTools();
    }
  
    public function changePasswordRequestLink($userId)
    {
        $user = $this->userRepository->getOne($userId, ['email']);
        $token = $this->userService->getToken($user['email']);
        return BASE_URL . '/userChangePassword/passwordSet/token/' . $token; 
    }
    
    public function requestIfPasswordEmpty($userId)
    {
        $user = $this->userRepository->getOne($userId, ['password']);
        if(empty($user['password'])){
            $url = $this->changePasswordRequestLink($userId);
            Redirect::to($url);
            die();
        }
    }
    
    public function write($token, $password)
    {
        $user = $this->userRepository->getByToken($token);
        
        $data['id'] = $user['id'];
        $data['password'] = $this->hashTools->setPassword($password)->encrypt();
        
        $where = ["token" => $token];
        return $this->userRepository->update($data, $where);
    }
    
    public function validate($password, $password2)
    {
        if(strlen($password) < 6){
            $this->errorMessage = "Senha muito curta";
            return false;
        }
        
        if($password != $password2){
            $this->errorMessage = "A senha e a confirmação não conferem";
            return false;
        }
        
        return true;
    }
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
