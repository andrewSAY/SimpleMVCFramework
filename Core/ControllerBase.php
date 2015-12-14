<?php

namespace LW\Core;


use LW\Core\UserSession\Authentication;
use LW\Core\UserSession\Session;

abstract class ControllerBase
{
    protected $request;
    protected $router;
    protected $response;
    protected $session;
    protected $authentication;
    protected $dataProvider;
    protected $viewer;


    function  __construct(Request $request, Response $response, Router $router, Session $session, Authentication $authentication, OrmProvider $dataProvider, Viewer $viewer)
    {
        $this->request = $request;
        $this->response = $response;
        $this->router = $router;
        $this->session = $session;
        $this->authentication = $authentication;
        $this->dataProvider = $dataProvider;
        $this->viewer = $viewer;
    }

    protected function  acceptSolutionRenderView($template, $params)
    {
        $solution = new Solution(SolutionTypes::RENDER_VIEW, array($template, $params));
        return $solution;
    }

    protected function  acceptSolutionRedirectInner($controller, $action, $params)
    {
        $solution = new Solution(SolutionTypes::REDIRECT_TO_CONTROLLER, array($controller, $action, $params));
        return $solution;
    }

    protected function  acceptSolutionRedirectToUrl($url)
    {
        $solution = new Solution(SolutionTypes::REDIRECT, array($url));
        return $solution;
    }

    protected function  acceptSolutionRedirectToRoute($route, $params = array())
    {
        $url = $this->router->getUrlByRoute($route, $params, false);
        $solution = new Solution(SolutionTypes::REDIRECT, array($url));
        return $solution;
    }

    protected function  acceptSolutionFlushToResponse($flushingData)
    {
        if(!is_array($flushingData))
        {
            $flushingData = array($flushingData);
        }
        $solution = new Solution(SolutionTypes::FLUSH_TO_RESPONSE, $flushingData);
        return $solution;
    }

} 