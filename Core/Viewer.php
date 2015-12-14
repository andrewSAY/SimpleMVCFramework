<?php


namespace LW\Core;


class Viewer
{
    /**
     * @var \Twig_Environment $twig
     */
    private  $twig;

    function __construct()
    {
        global $CONFIG;
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(PATH_TO_TEMPLATES);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => SITE_PATH.DS.$CONFIG['CACHE_FOLDER_NAME'].DS.'twig',
            'strict_variables' => true
        ));

        $this->twig->addExtension(new TwigExtensions());
    }

    /**
     *  For parameter 'templateName' it may be transmitted relative name of file with separator '::'
     * Example: render('subview::view.index.html', $data)
     * @param $templateName
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function render ($templateName, $data)
    {
        $templateName = str_replace('::', DS, $templateName);
        if(!file_exists(PATH_TO_TEMPLATES.DS.$templateName))
        {
            throw new \Exception('File not found '.PATH_TO_TEMPLATES.DS.$templateName);
        }
        return $this->twig->render($templateName, $data);
    }
} 