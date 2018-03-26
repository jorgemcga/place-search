<?php

namespace App\Controller;


use App\Model\MarkerService;
use App\Model\MarkerTypeService;
use App\Model\UserService;
use Lib\Sanitize;

class Marker extends AppController
{
    private $markerService;
    private $markerRepository;
    private $userService;
    private $userRepository;
    private $markerTypeService;
    private $markerTypeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->markerService = new MarkerService();
        $this->markerRepository = $this->markerService->getRepository();
        $this->userService = new UserService();
        $this->userRepository = $this->userService->getRepository();
        $this->markerTypeService = new MarkerTypeService();
        $this->markerTypeRepository = $this->markerTypeService->getRepository();

    }

    public function saveAction()
    {
        $dados = Sanitize::sanitizeAll($_POST);
        $dados["id"] = 0;
        try {
            $marker = $this->markerRepository->write($dados)->getLastInsertUpdateId();
        } catch (\Exception $e) {
            $marker = $e->getMessage();
        }
        return json_encode($marker);
    }

    public function getTypeAction()
    {
        $dados = Sanitize::sanitizeAll($_POST);
        $colunms = ["coord", "mark.title", "color", "mark.id", "mark.marker_type_id", "type.title as tipo", "obs", "end", "DATE_FORMAT(mark.created_at, '%d/%m/%Y - %H:%i:%s') as data", "user.name"];

        try {
            $markers = $this->markerRepository->find($colunms, "marker_type_id = {$dados['id']}", '', '', '');
        } catch (\Exception $e) {
            $markers = $e->getMessage();
        }

        if ($markers) {
            foreach ($markers as $key => $marker) {
                $markers[$key]["tipo"] = utf8_encode($marker["tipo"]);
            }
        }

        return json_encode($markers);
    }

    public function getAllAction()
    {
        $colunms = ["coord", "mark.title", "color", "mark.id", "mark.marker_type_id", "type.title as tipo", "obs", "end", "DATE_FORMAT(mark.created_at, '%d/%m/%Y - %H:%i:%s') as data", "user.name"];

        try {
            $markers = $this->markerRepository->find($colunms, "1 = 1", '', '', '');
        } catch (\Exception $e) {
            $markers = $e->getMessage();
            return json_encode($markers);
        }

        if ($markers) {
            foreach ($markers as $key => $marker) {
                $markers[$key]["tipo"] = utf8_encode($marker["tipo"]);
            }
        }

        return json_encode($markers);
    }
}