<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 27.08.15
 * Time: 9:36
 */

namespace LW\Core\UserSession;


class Session
{
    function __construct($autostart = true)
    {
        ini_set('session.use_cookies',1);
        ini_set('session.use_trans_sid',0);
        if($autostart)
        {
            $this->start();
        }
    }

    public function start()
    {
        if ( php_sapi_name() == 'cli' )
        {
            return;
        }
        if(session_status() != PHP_SESSION_ACTIVE)
        {
            session_start();
        }

    }

    public function close()
    {
        session_destroy();
    }

    public function remember($name, $value)
    {

        $_SESSION[$name] = $value;
    }

    public function forger($name)
    {
        if(array_key_exists($name, $_SESSION))
        {
            unset($_SESSION[$name]);
        }
    }

    public function get($name)
    {
        if(array_key_exists($name,$_SESSION))
        {
            return $_SESSION[$name];
        }

        return null;
    }

    public function commit()
    {
        session_write_close();
    }


} 