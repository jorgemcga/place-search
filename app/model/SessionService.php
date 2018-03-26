<?php

namespace App\Model;

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

use Aura\Session\SessionFactory;

class SessionService
{
    private $sessionService;
    private $segment = '';

    public function __construct()
    {
        //Aura session
        $sessionFactory = new SessionFactory();
        $sessionService = $sessionFactory->newInstance($_COOKIE);
        $this->segment = $sessionService->getSegment('App');
    }

    public function get($name)
    {
        return $this->segment->get($name);
    }

    public function set($name, $value)
    {
        return $this->segment->set($name, $value);
    }

}
