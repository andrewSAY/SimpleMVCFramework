<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 16:29
 */

namespace LW\Core;


class Request
{
    private $urlParts;
    private $hash;
    private $currentEncode;
    private $fileStorage;

    function __construct()
    {
        global $CONFIG;
        $this->currentEncode = $CONFIG['ENCODE_PAGE'];
        $this->fileStorage = SITE_PATH.DS.$CONFIG['FILE_STORAGE_FOLDER_NAME'];
        foreach ($_REQUEST as $key => $val)
        {
            if (isset($$key)) unset($$key);
        };
        if (get_magic_quotes_gpc())
        {
            $_GET = array_map('stripslashes', $_GET);
            $_POST = array_map('stripslashes', $_POST);
            $_COOKIE = array_map('stripslashes', $_COOKIE);
        };
        $this->setUrlParts();
        $this->setPost();
        $this->setGet();
        $this->setFile();
    }

    private function setUrlParts()
    {
        if(!isset($_SERVER['REQUEST_URI']))
        {
            $this->urlParts = array();
            return;
        }
        $this->urlParts = explode('/', $_SERVER['REQUEST_URI']);
        unset($this->uriParts[0]);
        $parts = $this->urlParts;
        foreach ($parts as $key => $value)
        {
            $this->uriParts[$key] = urldecode($value);
        }
    }

    private function setPost()
    {
        $this->hash['post'] = array();
        foreach ($_POST as $key => $value)
        {
            $value = $this->setCurrentEncode($value);
            $this->hash['post'][$key] = $value;
        }
        unset($_POST);
    }

    private function setGet()
    {
        $this->hash['get'] = array();
        foreach ($_GET as $key => $value)
        {
            $this->hash['get'][$key] = $value;
        }
        unset($_GET);
    }

    private function setFile()
    {
        $this->hash['files'] = array();
        foreach ($_FILES as $key => $value)
        {
            $this->hash['files'][$key] = $value;
        }
        unset($_FILES);
    }

    private function setCurrentEncode($value)
    {
        if (is_array($value))
        {
            foreach ($value as $key => $subValue)
            {
                $value[$key] = $this->setCurrentEncode($subValue);
            }
        } else
        {
            $value = mb_convert_encoding($value, $this->currentEncode);
        }
        return $value;
    }

    public function getUrlParts()
    {
        return $this->urlParts;
    }

    public function get($key, $source = 'all')
    {
        $source = strtolower($source);
        $value = null;
        if ($source == 'all')
        {
            if (array_key_exists($key, $this->hash['get']))
            {
                $value = $this->hash['get'][$key];
            }
            if (array_key_exists($key, $this->hash['post']))
            {
                $value = $this->hash['post'][$key];
            }
            if (array_key_exists($key, $this->hash['files']))
            {
                $value = $this->hash['files'][$key];
            }
            return $value;
        }
        if (array_key_exists($key, $this->hash[$source]))
        {
            $value = $this->hash[$source][$key];
        }

        return $value;
    }

    public function saveFileToStorage($savingFile, $newName)
    {
        return(move_uploaded_file($savingFile, $this->fileStorage.DS.$newName));
    }

    public function getMaxFileSize()
    {
        $size = ini_get('upload_max_filesize');
        if(!is_string($size))
        {
            return $size;
        }
        if(strpos($size, 'M') > -1)
        {
            $size = (str_replace('M','', $size)) * 1000000;
        }

        if(strpos($size, 'K') > -1)
        {
            $size = (str_replace('K','', $size)) * 1000;
        }

        return $size;
    }
} 