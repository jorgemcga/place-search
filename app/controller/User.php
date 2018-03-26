<?php

namespace App\Controller;

use App\Model\MarkerTypeService;
use App\Model\UserEmailValidateService;
use App\Model\UserResetPasswordService;
use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\CityService;
use Lib\DateTools;
use App\Model\UserChangePasswordService;

class User extends AppController
{

    private $userService;
    private $cityService;
    private $userResetPassword;
    private $userRepository;
    private $cityRepository;
    private $dateTools;
    private $userChangePasswordService;


    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        $this->userResetPassword = new UserResetPasswordService();
        $this->cityService = new CityService();
        $this->cityRepository = $this->cityService->getRepository();
        $this->dateTools = new DateTools();
        $this->userChangePasswordService = new UserChangePasswordService();
    }

    public function indexAction()
    {
        $this->validateAccess(['admin']);

        $markerTypes = new MarkerTypeService();
        $data["marcadores"] = $markerTypes->getRepository()->get();
        $data['users'] = $this->userRepository->get();

        return array("view" => "user/index", "data" => $data, "layout" => 'site');
    }

    public function editAction()
    {
        //$this->validateAccess(['admin', 'basic']);

        $id = (int) $this->getParam('id', 0);
        $userLogged = $this->authService->getUserData();
        
        if($this->adminLogged() === false && $userLogged['id'] != $id && $user['id'] != 0){
            die("Acesso não autorizado");
        }
        
        if ($id !== 0) {
            $data['user'] = $this->userRepository->getOne($id);
            $data['user']['birth_date'] = $this->dateTools->toBr($data['user']['birth_date'], false);
            $data['user']['city'] = $this->cityRepository->findOne([], "city_id = '{$data['user']['city_id']}'", 1);
        }
        
        return array("view" => "user/edit", "data" => $data, "layout" => 'site');
    }

    public function newPublicUserAction()
    {
        $user = Sanitize::sanitizeAll($_POST);
        $user['type'] = 'basic';
        $user['id'] = (int) isset($user['id']) ? $user['id'] : 0;

        if($this->userRepository->getByEmail($user["email"]))
        {
            FlashMsg::setMsgError("Esse e-mail já esta cadastrado!");
            return Redirect::to(BASE_URL );
        }

        $userId = $this->userRepository->write($user)->getLastInsertUpdateId();

        if($userId)
        {
            //$this->userChangePasswordService->requestIfPasswordEmpty($userId);

            $url = $this->userResetPassword->setEmail($user["email"])->request();
            if ($url === false)
            {
                FlashMsg::setMsgError("Desculpe, houve um erro ao processar a solicatação. Tente novamente");
                //$this->userResetPassword->getErrorMessage());
                $this->userRepository->delete($userId);
                return Redirect::to(BASE_URL );
            }
            else
            {
                //FlashMsg::setMsgOk("Solicitação recebida. Um e-mail foi enviado para o endereço requisitado.");
                return Redirect::to($url );
            }
        }

        FlashMsg::setMsgError("Desculpe, houve um erro ao processar a solicatação. Tente novamente");
        return Redirect::to(BASE_URL );
    }

    public function writeAction()
    {
        //$this->validateAccess(['admin', 'basic']);

        $userLogged = $this->authService->getUserData();
        
        $user = Sanitize::sanitizeAll($_POST);

        if($this->adminLogged() === false && $userLogged['id'] != $user['id'] && $user['id'] != 0){
            die("Acesso não autorizado");
        }
        
        $userLogged = $this->authService->getUserData();

        if (isset($userLogged['id']) && isset($user['id']) && $user['id'] == $userLogged['id'] && $userLogged['type'] == 'admin') {
            $user['type'] = 'admin';
        } else {
            $user['type'] = 'basic';
        }
        
        $user['id'] = (int) isset($user['id']) ? $user['id'] : 0;
        $user['city_id'] = (int) $user['city_id'];
        $user['birth_date'] = $this->dateTools->toUs($user['birth_date'], false);
        
        //Desvalida se e-mail for trocado
        if($user['id'] !== 0){
            $userNow = $this->userRepository->getOne($user['id']);
            if($userNow['email'] !== $user['email']){
                $user['email_valid'] = 0;
            }
        }
        
        $userId = $this->userRepository->write($user)->getLastInsertUpdateId();
        
        if($userId){
            FlashMsg::setMsgOk("Dados gravados com sucesso");
        } else {
            FlashMsg::setMsgError("Desculpe, houve um erro ao gravar");
        }
        
        $this->userChangePasswordService->requestIfPasswordEmpty($userId);
        Redirect::to(BASE_URL . '/user/edit/id/' . $userId);
    }

}
