<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use App\Model\LogService;
use App\Model\Repository\CityRepository;

class CityService
{

    protected $databaseService;
    protected $errorMessage = '';
    protected $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    public function getRepository()
    {
        return new CityRepository();
    }
}
