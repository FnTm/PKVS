<?php
error_reporting( E_ALL | E_STRICT );

define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
define('APPLICATION_PATH', BASE_PATH . '/application');

    defined('UPLOAD_PATH')
    || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/../public/images'));
        defined('APP_DOMAIN')
    || define('APP_DOMAIN',getenv('APP_DOMAIN'));
            defined('APP_PREF')
    || define('APP_PREF',getenv('APP_PREF'));
            defined('APPLICATION_DOMAIN')
    || define('APPLICATION_DOMAIN',getenv('APP_DOMAIN'));
    defined('RESOURCE_PATH')
    || define('RESOURCE_PATH', APPLICATION_DOMAIN . '/images');
        defined('BASE_URL')
    || define('BASE_URL', 'http://ssp');
defined('APP_LOG_PATH') ||
 define('APP_LOG_PATH', realpath(dirname(__FILE__) . '/../logs'));
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('CONFIG_FILE',APPLICATION_PATH . '/configs/application.ini');

// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . BASE_PATH . '/../'
    . PATH_SEPARATOR . get_include_path()
);

// Define application environmentGUI

/** Zend_Application */
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();
require_once 'Zend/Application.php';

// Create application, bootstrap, and run

require_once 'controllers/ControllerTestCase.php';