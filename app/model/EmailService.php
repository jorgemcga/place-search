<?php

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model;

use Lib\Email;

class EmailService extends Email
{

    public function __construct()
    {
        parent::__construct();
        
        $this->setHost(EMAIL_HOST);
        $this->setPort(EMAIL_PORT);
        $this->setUsername(EMAIL_USERNAME);
        $this->setPassword(EMAIL_PASSWORD);
        $this->setSmtpSecure(EMAIL_SMTPSECURE);
    }
}
