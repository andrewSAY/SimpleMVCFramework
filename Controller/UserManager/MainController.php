<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 12:14
 */

namespace LW\Controller\UserManager;

use LW\Core\ControllerBase;

class MainController extends ControllerBase
{
    public function indexAction()
    {
        return $this->acceptSolutionRenderView('user_manager::index.html.twig', array());
    }
} 