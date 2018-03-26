<?php

namespace App\Controller;

use App\Model\AuthService;
use App\Model\LogService;
use Edily\Base\Redirect;
use Lib\FlashMsg;
use App\Model\UserEmailValidateService;
use Lib\UrlTools;
use App\Model\UserService;

abstract class AppController extends \Edily\Base\BaseController
{

    public $logService;
    public $authService;
    private $userEmailValidateService;
    private $urlTools;
    private $userService;
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
        $this->logService = new LogService();
        $this->userEmailValidateService = new UserEmailValidateService();
        $this->urlTools = new UrlTools();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        
        $this->checkUserEmailValidated();
    }

    protected function validateAccess($type)
    {
        if ($this->authService->authValidade($type) === false) {
            FlashMsg::setMsgError("Essa página tem acesso restrito.");
            return Redirect::to(BASE_URL . 'auth/login');
        }
    }

    protected function adminLogged()
    {
        $userLogged = $this->authService->getUserData();
        if (isset($userLogged['type']) && $userLogged['type'] == 'admin') {
            return true;
        }
        return false;
    }
    
    protected function checkUserEmailValidated()
    {
        $userLogged = $this->authService->getUserData();
        
        $route = \Edily\Base\Register::get('route');
        
        if($route->controller == 'userEmailValidate'){
            return false;
        }
        
        if(!isset($userLogged['id'])){
            return false;
        }
        
        if($this->userEmailValidateService->checkIfEmailValidated($userLogged['id']) === false){
            $user = $this->userRepository->getOne($userLogged['id']);
            $emailAscii = $this->urlTools->encode($user['email']);
            Redirect::to(BASE_URL . 'userEmailValidate/send/email/' . $emailAscii);
        }
    }
}
