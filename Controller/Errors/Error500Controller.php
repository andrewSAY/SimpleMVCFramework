<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 20.08.15
 * Time: 17:51
 */

namespace LW\Controller\Errors;

use LW\Core\ControllerBase;

class Error500Controller extends ControllerBase
{

    public function indexAction()
    {
        $this->response->setStatus(500);
        return $this->acceptSolutionRenderView('error::500.html.twig', array());
    }
} 