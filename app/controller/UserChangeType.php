<?php

namespace App\Controller;

use App\Model\MarkerTypeService;
use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\UserService;
use App\Model\UserChangeTypeService;

class UserChangeType extends AppController
{

    private $userService;
    private $userChangeTypeService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->userChangeTypeService = new UserChangeTypeService();
    }

    public function typeSetAction()
    {
        $data = [];
        $markerTypes = new MarkerTypeService();
        $data["marcadores"] = $markerTypes->getRepository()->get();

        $this->validateAccess(['admin']);
        $data['userId'] = (int) $this->getParam('id');
        return array("view" => "user-change-type/type-set", "data" => $data, "layout" => 'site');
    }

    public function writeAction()
    {
        $this->validateAccess(['admin']);
        
        $id = (int) filter_input(INPUT_POST, 'id');
        $type = Sanitize::sanitizeString(filter_input(INPUT_POST, 'type'));

        if ($this->userChangeTypeService->write($id, $type) === false) {
            FlashMsg::setMsgError($this->userChangeTypeService->getErrorMessage());
        } else {
            FlashMsg::setMsgOk("Tipo alterado com sucesso");
        }
        
        Redirect::to(BASE_URL . 'user/edit/id/' . $id);
    }
}
