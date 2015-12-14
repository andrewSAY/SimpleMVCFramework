<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 27.08.15
 * Time: 12:43
 */

namespace LW\Core;


use LW\Core\UserSession\UserInterface;

class AccessControl
{
    function __construct()
    {}

    public function checkAccess(UserInterface $user, $route)
    {
        global $CONFIG;
        $accessList = $CONFIG['ACCESS_TO_ROUTES'];
        $accessOpen = true;
        foreach($accessList as $item)
        {
            if(preg_match($item['pattern'], $route))
            {
                if(!in_array($user->getRole(), $item['roles']))
                {
                    $accessOpen = false;
                }
                break;
            }
        }

        return $accessOpen;
    }

} 