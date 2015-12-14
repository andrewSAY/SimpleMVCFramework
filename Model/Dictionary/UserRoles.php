<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 08.05.15
 * Time: 10:37
 */

namespace LW\Model\Dictionary;


class  UserRoles extends DictionaryAbstract
{
    public  $ROLE = array('ROLE', 'Без роли');
    public  $ROLE_ADMIN = array('ROLE_ADMIN', 'Администратор системы');
    public  $ROLE_BOSS = array('ROLE_BOSS','Руководитель (начальник)');
    public  $ROLE_WORKER = array('ROLE_WORKER','Работник (исполнитель)');
} 