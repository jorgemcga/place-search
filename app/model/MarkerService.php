<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 13/01/2018
 * Time: 18:22
 */

namespace App\Model;


use App\Model\Repository\MarkerRepository;

class MarkerService
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
        return new MarkerRepository();
    }
}