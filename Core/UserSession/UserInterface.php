<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 27.08.15
 * Time: 9:37
 */

namespace LW\Core\UserSession;


interface UserInterface
{
    public function getId();
    public function getUsername();
    public function setUsername($name);
    public function getPassword();
    public function setPassword($pass);
    public function getRole();
    public function getIsActive();
} 