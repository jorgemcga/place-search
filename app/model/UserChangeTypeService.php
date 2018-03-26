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

class UserChangeTypeService
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
  
    public function write($id, $type)
    {
        $user = $this->userRepository->getOne($id);
        
        $data['id'] = $user['id'];
        $data['type'] = $type;
        $where = ["id" => $id];
        return $this->userRepository->update($data, $where);
    }
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
