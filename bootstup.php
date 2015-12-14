<?php
$sitePath = realpath(dirname(__FILE__));

define ('DS', DIRECTORY_SEPARATOR); // разделитель для путей к файлам
define ('SITE_PATH', $sitePath); // путь к корневой папке сайта
define('PATH_TO_CONTROLLERS', SITE_PATH . DS . 'Controller');
define('PATH_TO_MODELS', SITE_PATH . DS . 'Model');
define('PATH_TO_CORE', SITE_PATH . DS . 'Core');
define('PATH_TO_TEMPLATES', SITE_PATH . DS . 'view');
define('PATH_TO_ENTITIES_ORM', SITE_PATH . DS . 'Model'.DS.'Entity');

include_once(PATH_TO_CORE.DS.'Autoload.php');
include_once(SITE_PATH . DS .'config'.DS.'base.php');
include_once(SITE_PATH . DS .'config'.DS.'access.php');
include_once(SITE_PATH . DS .'config'.DS.'data_base.php');
include_once(SITE_PATH . DS .'config'.DS.'routes.php');


$CONFIG = array('DB_CONFIG' => $db_config,
    'ROUTES' => $routes,
    'ENCODE_PAGE' => $encode_page,
    'CACHE_FOLDER_NAME' => $cache_folder_name,
    'FILE_STORAGE_FOLDER_NAME' => $file_storage_folder_name,
    'USER_CLASS' => $user_class,
    'ACCESS_TO_ROUTES' => $access,
);

unset($db_config);
unset($routes);
unset($encode_page);
unset($cache_folder_name);
unset($file_storage_folder_name);
unset($user_class);
unset($access);

header('Content-Type: text/html; charset=' . $CONFIG['ENCODE_PAGE']);
mb_internal_encoding($CONFIG['ENCODE_PAGE']);

spl_autoload_register(array('\LW\core\Autoload', 'loadClass'));

\LW\Core\Autoload::initLibs();

function redirectErrorMessageToException($errno, $errstr, $errfile, $errline ) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('redirectErrorMessageToException');

$APP = new \LW\Core\Application();