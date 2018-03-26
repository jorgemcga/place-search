<?php
namespace Lib;

use Edily\Base\Register;

/**
 * Description of MsgHelper
 *
 * @author Edily Cesar Medule - edilycesar@gmail.com - www.jeitodigital.com.br
 */
class FlashMsg
{
    public static function setMsgError($msg, $ttl = 0)
    {
        Register::set('_msgError', $msg);
        Register::set('_msgErrorTtl', $ttl);
    }
    
    public static function getMsgError()
    {
        $msg = Register::get('_msgError');
        $ttl = Register::get('_msgErrorTtl');
        Register::set('_msgErrorTtl', $ttl-1);
        if ($ttl <= 0) {
            Register::kill('_msgError');
        }
        return $msg;
    }
    
    public static function setMsgOk($msg, $ttl = 0)
    {
        Register::set('_msgOk', $msg);
        Register::set('_msgOkTtl', $ttl);
    }
    
    public static function getMsgOk()
    {
        $msg = Register::get('_msgOk');
        $ttl = Register::get('_msgOkTtl');
        Register::set('_msgOkTtl', $ttl-1);
        if ($ttl <= 0) {
            Register::kill('_msgOk');
        }
        return $msg;
    }
}
