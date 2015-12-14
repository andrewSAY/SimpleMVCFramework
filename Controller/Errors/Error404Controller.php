<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 20.08.15
 * Time: 17:51
 */

namespace LW\Controller\Errors;

use LW\Core\ControllerBase;

class Error404Controller extends ControllerBase
{

    public function indexAction()
    {
        $this->response->setStatus(404);
        return $this->acceptSolutionRenderView('error::404.html.twig', array());
    }
} 