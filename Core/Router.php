<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 19.08.15
 * Time: 15:52
 */

namespace LW\core;


class Router
{
    private $routes;
    private $uriParts;
    private $currentRoute;
    private $apacheRewriteMode;

    function __construct(Request $request)
    {
        global $CONFIG;
        $this->routes = $CONFIG['ROUTES'];
        $this->uriParts = $request->getUrlParts();
        $this->currentRoute = null;

        $this->apacheRewriteMode = 0;
        if (count($this->uriParts) > 0)
        {
            if ($this->uriParts[1] == 'index.php')
            {
                $this->apacheRewriteMode = 1;
            }
        }
    }

    public function getCurrentRoute()
    {
        if (!is_null($this->currentRoute))
        {
            return $this->currentRoute;
        }

        $routeName = '';

        if ($this->uriParts[1 + $this->apacheRewriteMode] == '')
        {
            if (count($this->routes) == 0)
            {
                throw new \Exception('Don`t set default route');
            }
            if (array_key_exists($routeName, $this->routes))
            {
                throw new \Exception('Don`t set default route');
            }

            $routeName = 'default';
        } else
        {

            if (array_key_exists($this->uriParts[1 + $this->apacheRewriteMode], $this->routes))
            {
                $routeName = $this->uriParts[1 + $this->apacheRewriteMode];
            }

        }

        $this->currentRoute = $routeName;

        if ($routeName == '')
        {
            throw new \Exception('Not found match for route ' . $this->uriParts[1]);
        }
        return $routeName;
    }

    public function getRouteDate($routeName)
    {
        if ($this->routes[$routeName]['controller'] == '')
        {
            throw new \Exception('Don`t set name of controller for route ' . $routeName);
        }

        if (($this->routes[$routeName]['action']) == '')
        {
            throw new \Exception('Don`t set name of action for route ' . $routeName);
        }

        $controller = $this->routes[$routeName]['controller'] . 'Controller';
        $action = $this->routes[$routeName]['action'] . 'Action';
        $params = $this->getParams($routeName);

        return array(
            'controller' => $controller,
            'action' => $action,
            'params' => $params
        );
    }

    public function getUrlByRoute($routeName, $params = array(), $absolute = true)
    {
        if (!is_array($params))
        {
            throw new \Exception('Variable "params" must be array ');
        }
        $response = new Response();
        if (array_key_exists($routeName, $this->routes))
        {
            $route = $this->routes[$routeName];
        } else
        {
            throw new \Exception('Not found match for route ' . $routeName);
        }
        $url = '';
        if ($this->apacheRewriteMode == 1)
        {
            $url = '/index.php';
        }
        if ($absolute)
        {
            $url = $response->getPrepareUrl() . $url;
        }
        $url = $url . '/' . $routeName;

        if (count($route['params']) == 0)
        {
            return $url;
        }

        if (count($params) == 0)
        {
            throw new \Exception('Don`t set value for all parameters of route ' . $routeName);
        }

        foreach ($params as $param)
        {
            $url = $url . '/' . $param;
        }

        return $url;
    }

    private function  getParams($routeName)
    {
        $params = $this->routes[$routeName]['params'];
        $uriPartsCount = count($this->uriParts);

        if (count($params) == 0)
        {
            return $params;
        }


        foreach ($params as $key => $value)
        {
            if ($key + 2 + $this->apacheRewriteMode <= $uriPartsCount)
            {
                $params[$key] = $this->uriParts[$key + 2 + $this->apacheRewriteMode];
            } else
            {
                throw new \Exception('Don`t set value of parameter "' . $value . '" for route ' . $routeName);
            }
        }

        return $params;
    }

} 