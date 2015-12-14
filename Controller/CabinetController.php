<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 11:18
 */

namespace LW\Controller;


use LW\Core\ControllerBase;

class CabinetController extends ControllerBase
{
    public function indexAction()
    {
        return $this->acceptSolutionRenderView('cabinet::index.html.twig', array());
    }
} 