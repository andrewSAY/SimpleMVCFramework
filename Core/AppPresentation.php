<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 12:10
 */

namespace LW\Core;


use LW\Core\UserSession\UserInterface;

class AppPresentation
{
    private $user;
    private $router;
    private $response;

    function __construct(UserInterface $user, Router $router, Response $response)
    {
        $this->user = $user;
        $this->router = $router;
        $this->response = $response;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRouteName()
    {
        return $this->router->getCurrentRoute();
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getResponse()
    {
        return $this->response;
    }
} 