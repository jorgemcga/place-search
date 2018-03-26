<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 27/01/2018
 * Time: 16:45
 */

namespace App\Model;


use App\Model\Repository\MarkerThemeRepository;

class MarkerThemeService
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
        return new MarkerThemeRepository();
    }
}

