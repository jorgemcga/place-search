<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use App\Model\LogService;
use App\Model\Repository\UserRepository;
use Lib\HashTools;
use Edily\Base\Redirect;

class UserService
{

    protected $databaseService;
    protected $errorMessage = '';
    protected $logService;
    protected $userRepository = null;
    protected $hashTools;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->userRepository = new UserRepository();
        $this->hashTools = new HashTools();
    }

    public function getToken($email)
    {
        $user = $this->userRepository->getByEmail($email);

        if (empty($user['token'])) {
            return $this->resetToken($email);
        }

        return $user['token'];
    }

    public function resetToken($email)
    {
        $user = $this->userRepository->getByEmail($email);
        $newToken = $this->generateToken($email);
        $user['token'] = $newToken;
        $this->userRepository->write($user);

        $user = $this->userRepository->getByEmail($email);
        if (empty($user['token']) || $user['token'] !== $newToken) {
            throw new Exception("Erro ao gerar novo token");
        }

        return $user['token'];
    }

    public function checkIfEmailExist($email)
    {
        return $this->userRepository->getByEmail($email) !== false ? true : false;
    }

    private function generateToken($email)
    {
        return $this->hashTools->setPassword($email)
                        ->encrypt();
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getRepository()
    {
        return $this->userRepository;
    }

}
