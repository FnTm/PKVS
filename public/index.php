<?php
ob_start("ob_gzhandler");
header("Content-type: text/html; charset: UTF-8");
//header("Cache-Control: must-revalidate");
//$offset = 20 ;
//$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
//header($ExpStr);
// Define path to application directory
defined('APPLICATION_PATH') ||
 define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('UPLOAD_PATH') ||
 define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/../public/images/uploaded'));
defined('ATTACHMENT_PATH') ||
 define('ATTACHMENT_PATH', realpath(dirname(__FILE__) . '/../public/attachments/uploaded'));
defined('APP_DOMAIN') || define('APP_DOMAIN', "http://".$_SERVER['HTTP_HOST']);
defined('APP_PREF') || define('APP_PREF', getenv('APP_PREF'));
defined('APPLICATION_DOMAIN') ||
 define('APPLICATION_DOMAIN', getenv('APP_DOMAIN'));
defined('RESOURCE_PATH') ||
 define('RESOURCE_PATH', APPLICATION_DOMAIN . '/images');
defined('ATTACHMENT_RESOURCE_PATH') ||
 define('ATTACHMENT_RESOURCE_PATH', APPLICATION_DOMAIN . '/attachments');
defined('BASE_URL') || define('BASE_URL', APP_DOMAIN);
defined('APP_LOG_PATH') ||
 define('APP_LOG_PATH', realpath(dirname(__FILE__) . '/../logs'));
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'testing');
// Ensure library/ is on include_path
set_include_path(
implode(PATH_SEPARATOR,
array(realpath(APPLICATION_PATH . '/../library'), get_include_path())));
define('CONFIG_FILE', APPLICATION_PATH . '/configs/application.ini');
/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap()->run();