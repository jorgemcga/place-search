<?php

/* 
 * By JMA
 */

namespace App\Model;

use App\Model\Repository\MarkerTypeRepository;

class MarkerTypeService
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
        return new MarkerTypeRepository();
    }
}

