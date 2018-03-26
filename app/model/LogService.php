<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogService
{

    private $errorMessage = '';
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('Dr-test');
        $filepath = __DIR__ . '/../../../public/files/app.log';
        $this->logger->pushHandler(new StreamHandler($filepath, Logger::WARNING));
    }

    public function logWarning($message = '')
    {
        return $this->logger->warning($message, $this->getContext());
    }

    public function logError($message = '')
    {
        return $this->logger->error($message, $this->getContext());
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    private function getContext()
    {
        $route = \Edily\Base\Register::get('route');

        $controller = isset($route->controller) ? $route->controller : '-';
        $action = isset($route->action) ? $route->action : '-';

        return [
            $controller,
            $action
        ];
    }

}
