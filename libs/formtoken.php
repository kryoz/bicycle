<?php
/**
 * This class helps to protect forms from CSRF vulnarability
 * 
 * @author kryoz
 */
class FormToken
{
    const FIELDNAME = 'formtoken';
    const TTL = '7200';
    
    private function getSessionToken($prop)
    {
        session_start();
        return isset($_SESSION[self::FIELDNAME][$prop]) ? $_SESSION[self::FIELDNAME][$prop] : false;
    }
    
    private function setSessionToken($prop, $val)
    {
        session_start();
        $_SESSION[self::FIELDNAME][$prop] = $val;
    }
    
    public static function getToken()
    {
        session_start();
        $time = time();
        $token = sha1(mt_rand(0, 1000000));
        
        self::setSessionToken($token, $time);
        
        return "<input name='" . self::FIELDNAME . "' value='{$token}' type='hidden' />";
    }
    
    public static function validateToken($clear = true)
    {
        session_start();
        $valid = false;
        $posted = isset($_REQUEST[self::FIELDNAME]) ? $_REQUEST[self::FIELDNAME] : '';

        if (!empty($posted)) {
            if ( self::getSessionToken($posted) ) {
                 if ( self::getSessionToken($posted) >= time() - self::TTL) {
                    $valid = true;
                 }
                 if ($clear) unset($_SESSION[self::FIELDNAME][$posted]);
            }
        }

        return $valid;
    }
}
