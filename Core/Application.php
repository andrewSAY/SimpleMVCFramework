<?php


namespace LW\Core;


use LW\Core\UserSession\Authentication;
use LW\Core\UserSession\Session;

class Application
{
    private $request;
    private $router;
    private $response;
    private $session;
    private $authentication;
    private $dataProvider;
    private $appPresentation;
    private $viewer;

    function __construct()
    {
        if (php_sapi_name() == 'cli')
        {
            return;
        }

        $this->request = new Request();
        $this->router = new Router($this->request);
        $this->response = new Response();
        $this->session = new Session();
        $this->dataProvider = new OrmProvider();
        $this->authentication = new Authentication($this->session, $this->dataProvider);
        $this->viewer = new Viewer();
    }

    public function run()
    {
        try
        {
            $currentRoute = $this->router->getCurrentRoute();
        } catch (\Exception $exc)
        {
            $this->errorBreak($exc, '404');
            return;
        }

        try
        {
            $this->session->start();
            $currentUser = $this->authentication->getCurrentUser();

            $this->appPresentation = new AppPresentation($currentUser, $this->router, $this->response);

            $accessControl = new AccessControl();
            if (!$accessControl->checkAccess($currentUser, $currentRoute))
            {
                $this->errorBreak(new \Exception('Don`t access to route ' . $currentRoute . ' for user ' . $currentUser->getUsername()), '403');
                return;
            }
            $routeData = $this->router->getRouteDate($currentRoute);
            $solution = $this->executeAction($routeData['controller'], $routeData['action'], $routeData['params']);
            $this->applySolution($solution);
        } catch (\Exception $exc)
        {
            $this->errorBreak($exc, '500');
            return;
        }

    }

    public function getAppPresentation()
    {
        try
        {
            if (is_null($this->appPresentation))
            {
                $this->appPresentation = new AppPresentation($this->authentication->getCurrentUser(), $this->router, $this->response);
            }
        } catch (\Exception $exc)
        {
            $this->errorBreak($exc, '500');
        }

        return $this->appPresentation;
    }


    private function executeAction($controllerName, $actionName, $params)
    {
        $controllerObject = new $controllerName($this->request, $this->response, $this->router, $this->session, $this->authentication, $this->dataProvider, $this->viewer);
        if (!method_exists($controllerObject, $actionName))
        {
            throw new \Exception('Method not found ' . $actionName . ' for controller ' . $controllerName);
        }
        return call_user_func_array(array($controllerObject, $actionName), $params);
    }

    /**
     * @param Solution $solution
     * @throws \Exception
     */
    private function applySolution($solution)
    {
        if (!is_array($solution->getData()))
        {
            throw new \Exception('Variable "data" in object of type of Solution must be array');
        }
        switch ($solution->getType())
        {
            case SolutionTypes::RENDER_VIEW:
                $this->renderView($solution->getData());
                break;
            case SolutionTypes::REDIRECT_TO_CONTROLLER:
                $this->redirectToController($solution->getData());
                break;
            case SolutionTypes::REDIRECT:
                $this->redirect($solution->getData());
                break;
            case SolutionTypes::FLUSH_FILE:
                $this->flushFile($solution->getData());
                break;
            case SolutionTypes::FLUSH_TO_RESPONSE:
                $this->flushToResponse($solution->getData());
                break;
            default:
                break;
        }

    }

    private function errorBreak(\Exception $exc, $errorCode)
    {
        $errorMessage = date("\n" . 'd.m.Y h:m:s', time()) . ' ' . $exc->getMessage() . '. File: ' . $exc->getFile() . ' Line: ' . $exc->getLine();
        file_put_contents(SITE_PATH . DS . 'errors.txt', $errorMessage, FILE_APPEND);
        $route = $this->router->getRouteDate($errorCode);
        $this->applySolution($this->executeAction($route['controller'], $route['action'], $route['params']));
    }


    private function renderView($data)
    {
        echo($this->viewer->render($data[0], $data[1]));
    }

    private function redirectToController($data)
    {
        try
        {
            $solution = $this->executeAction($data[0], $data[1], $data[2]);
            $this->applySolution($solution);
        } catch (\Exception $exc)
        {

            $this->errorBreak($exc, $this->router, '500');
            return;
        }
    }

    private function redirect($data)
    {
        $response = new Response();
        $response->redirect($data[0]);
    }

    private function flushFile($data)
    {
    }

    private function flushToResponse($data)
    {
        echo($data[0]);
    }

} 