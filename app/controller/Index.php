<?php
namespace App\Controller;

use App\Model\MarkerThemeService;
use App\Model\MarkerTypeService;

class Index extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        return array("view" => "index/index", "data" => [], "layout" => 'site');
    }

    public function responsiveAction()
    {
        return array("view" => "index/index", "data" => [], "layout" => 'layout');
    }
}