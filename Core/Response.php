<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 20.08.15
 * Time: 17:29
 */

namespace LW\Core;


class Response
{
    private  $host;

    function __construct()
    {
        $this->host = $_SERVER['HTTP_HOST'];
    }

    public function redirect($url, $protocol = 'http')
    {
        $protocol = $this->getPrepareUrl($protocol);
        header('Location:'.$protocol.$url);
    }

    public function prepareForJson()
    {
        header('Content-Type: application/json');
    }

    public function setStatus($statusCode)
    {
        switch($statusCode)
        {
            case 302: $status = '302 Redirect';
                break;
            case 403: $status = '403 Access denied';
                break;
            case 404: $status = '404 Not found';
                break;
            case 500: $status = '500 Server error';
                break;
            default: $status = '200 Ok';
                break;
        }
        header('HTTP/1.1 '.$status);
        header('Status: '.$status);
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPrepareUrl($protocol = 'http')
    {
        return $protocol.'://'.$this->host;
    }
} 