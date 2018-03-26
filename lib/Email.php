<?php

namespace Lib;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Description of EmailHelper
 *
 * @author edily
 */
class Email
{

    private $subject;
    private $message;
    private $from;
    private $fromName;
    private $host;
    private $username;
    private $password;
    private $port;
    private $to;
    private $replyTo;
    private $cc;
    private $attachment;
    private $confirmReadingTo;
    private $errorInfo;
    private $mail;
    private $debug = false;
    private $smtpSecure = 'SSL';

    public function __construct()
    {
        $this->mail = new PHPMailer();
    }

    private function setup()
    {
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = $this->smtpSecure;
        $this->mail->SMTPDebug = 0;
        $this->mail->Timeout = 10;
        $this->mail->CharSet = "UTF-8";

        $this->mail->isSMTP();
        $this->mail->Host = $this->host;
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        $this->mail->Port = $this->port;

        $this->mail->From = $this->from;
        $this->mail->FromName = $this->fromName;
        $this->mail->ConfirmReadingTo = $this->confirmReadingTo;
        $this->mail->Subject = $this->subject;

        $this->mail->AltBody = strip_tags($this->message);
        //$this->mail->Body = $this->message;
        $this->mail->msgHTML($this->message);

        $this->mail->addAddress($this->to);

        if (!empty($this->attachment)) {
            $this->mail->addAttachment($this->attachment);
        }

        if (!empty($this->replyTo)) {
            $this->mail->addReplyTo($this->replyTo);
        }

        if (!empty($this->cc)) {
            $this->mail->addCC($this->cc);
        }
    }

    public function send()
    {
        $this->setup();
        $send = $this->mail->send();
        $this->errorInfo = $this->mail->ErrorInfo;
        return $send;
    }

    /*
     * Getters and setters
     */

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setFronName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    public function setConfirmReadingTo($confirmReadingTo)
    {
        $this->confirmReadingTo = $confirmReadingTo;
        return $this;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
    
    public function setSmtpSecure($smtpSecure)
    {
        $this->smtpSecure = $smtpSecure;
        return $this;
    }

}
