<?php

namespace LW\Core;


class Autoload
{
    public static function initLibs()
    {
        include_once(SITE_PATH . DS . 'libs' . DS . 'twig' . DS . 'vendor' . DS . 'autoload.php');
        include_once(SITE_PATH . DS . 'libs' . DS . 'doctrine' . DS . 'vendor' . DS . 'autoload.php');
    }

    public static function loadClass($className)
    {
        self::addPrefix($className);
        $className = $className . '.php';
        //echo($className.'<br/>');
        if (file_exists($className))
        {
            //echo($className.'<br/>');
            include_once($className);
        }
    }

    private static function  addPrefix(&$className)
    {
        $className = str_replace('\\', DS, $className);
        if (substr($className, 0, 3) == 'LW' . DS)
        {
            $className = str_replace('LW' . DS, SITE_PATH . DS, $className);
            return;
        }
        if (substr($className, 0, 33) == 'Symfony' . DS . 'Component' . DS . 'Console' . DS . 'Helper' . DS)
        {
            $className = substr($className, 33, strlen($className) - 33);
            $className = SITE_PATH . DS . 'libs' . DS . 'doctrine' . DS . 'vendor' . DS . 'symfony' . DS . 'console' . DS . $className;
            return;
        }

    }

} 