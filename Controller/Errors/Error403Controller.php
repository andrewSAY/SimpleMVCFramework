<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 20.08.15
 * Time: 17:51
 */

namespace LW\Controller\Errors;

use LW\Core\ControllerBase;

class Error403Controller extends ControllerBase
{

    public function indexAction()
    {
        $this->response->setStatus(403);
        return $this->acceptSolutionRenderView('error::403.html.twig', array());
    }
} 