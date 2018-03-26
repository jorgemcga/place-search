<?php

namespace App\Controller;

use Lib\Sanitize;
use Lib\FlashMsg;
use Edily\Base\Redirect;
use App\Model\CityService;

class City extends AppController
{

    private $cityService;
    private $cityRepository;

    public function __construct()
    {
        parent::__construct();        
        $this->cityService = new CityService();  
        $this->cityRepository = $this->cityService->getRepository();
    }

    public function getJsonAction()
    {
        $search = filter_input(INPUT_POST, 'search');
        $cityes = $this->cityRepository->find([], "city_name LIKE '{$search}%'", 30, 'city_name ASC', 'city_id');
        return json_encode($cityes);
    }
}
