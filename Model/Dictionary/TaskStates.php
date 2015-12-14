<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.09.15
 * Time: 16:51
 */

namespace LW\Model\Dictionary;


class TaskStates extends DictionaryAbstract
{
    public  $CREATED = array('CREATED', 'Создана');
    public  $RUNNING = array('RUNNING', 'Выполняется');
    public  $COMPLETED = array('COMPLETED', 'Завершена');
    public  $CANCELED = array('CANCELED', 'Отменена');
    public  $ON_REVIEW = array('ON_REVIEW', 'Рассматривается постановщиком');
    public  $RETURNED = array('RETURNED', 'Возвращена на доработку');
} 