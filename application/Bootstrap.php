<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_acl = null;

    protected function _initAutoload()
    {
        $modelLoader = new Zend_Application_Module_Autoloader(
            array('namespace' => '',
                'basePath' => APPLICATION_PATH . '/modules/default'));

        return $modelLoader;
    }

    protected function _initloadDraugiemConfig()
    {
        $draugiemConf = new Zend_Config_Ini(APPLICATION_PATH . '/configs/draugiem.config.ini', APPLICATION_ENV);
        Zend_Registry::set("draugiemOptions", $draugiemConf->draugiem);
        /** @var $draugiem Zend_Config_Ini */
        /*$draugiem=Zend_Registry::get("draugiemOptions");
       var_dump($draugiem->secret);
        */
    }

    protected function _initACL()
    {

        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Registry::set('role',
                Zend_Auth::getInstance()->getStorage()->read()->role);
        } else {
            Zend_Registry::set('role', 'guest');
        }
        $this->_acl = new Model_AuthAcl();
        $this->_auth = Zend_Auth::getInstance();
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Plugin_AccessCheck($this->_acl));
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->setHelperPath(APPLICATION_PATH . '/helpers', '');
        $view->addHelperPath("Sozfo/View/Helper", "Sozfo_View_Helper");
        $view->addHelperPath("JP/Controller/Helper", "JP_Controller_Helper");
        $view->addHelperPath("JP/View/Helper", "JP_View_Helper");
        $view->addHelperPath("ZendX/JQuery/View/Helper",
            "ZendX_JQuery_View_Helper");
        Zend_Controller_Action_HelperBroker::addHelper(
            new JP_Controller_Helper_AuthenticationLoader());
        $view->doctype('HTML4_STRICT');
        $view->headMeta()
            ->appendHttpEquiv('Content-type', 'text/html;charset=utf-8')
            ->appendName('description', 'TDA Pūpolītis');
        $view->headTitle()
            ->setSeparator(' - ')
            ->headTitle('TDA Pūpolītis');
    }

    protected function _initAutoloaders()
    {
        $this->getApplication()->setAutoloaderNamespaces(
            array('JP_'));
        return $this;
        /** $controller = Zend_Controller_Front::getInstance();
        $controller->setBaseUrl('/public');
         * **/
    }

    protected function _initJqueryLoad()
    {
        $view = new Zend_View();
        // $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $view->addHelperPath("ZendX/JQuery/View/Helper",
            "ZendX_JQuery_View_Helper");
        $view->jQuery()
            ->addStylesheet(
            '/js/jquery/css/ui-lightness/jquery-ui-1.7.2.custom.css')
            ->setLocalPath('/js/jquery/jquery.php')
            ->setUiLocalPath('/js/jquery-ui-1.8.16.custom.min.php');
        $view->jQuery()->enable();
        ZendX_JQuery::enableView($view);
        return $view;
    }

    protected function _initLayoutHelper()
    {
        $this->bootstrap('frontController');
        $layout = Zend_Controller_Action_HelperBroker::addHelper(
            new JP_Controller_Helper_LayoutLoader());
    }

    protected function _initDataCache()
    {
        $frontend = array("lifetime" => 360000, // cache lifetime of 10 hours (time is in seconds)
            "automatic_serialization" => true); //default is false
        $cachedir = APPLICATION_PATH . "/../tmp";
        if (!is_dir($cachedir)) {
            mkdir($cachedir, 0755);
        }
        $backend = array('cache_dir' => $cachedir);
        // Getting a Zend_Cache_Core object
        $zend_cache = Zend_Cache::factory("Core", "File", $frontend,
            $backend);
        Zend_Registry::set('cache', $zend_cache);
        $frontend = array("lifetime" => 720, // cache lifetime of 10 hours (time is in seconds)
            "automatic_serialization" => true); //default is false
        $short_cache = Zend_Cache::factory("Core", "File", $frontend,
            $backend);
        Zend_Registry::set('shortcache', $short_cache);
        Zend_Db_Table_Abstract::setDefaultMetadataCache($zend_cache);
    }


    protected function _initDb()
    {
        $resource = $this->getPluginResource('multidb');
        Zend_Registry::set("multidb", $resource);
    }

    protected function _initLoggers()
    {
        $loggers = array('framework', 'database', 'import', 'admin', 'login',
            'minifeed', 'signoff', 'notification', 'cron', 'newsletter', 'mail',
            'upload', 'application', 'pdf', 'export');
        Zend_Registry::set('loggers', $loggers);
        $logFormat = date('r') . ', "%message%" (%priorityName%)' . PHP_EOL;
        $simpleFormatter = new Zend_Log_Formatter_Simple($logFormat);
        foreach ($loggers as $aLogger) {
            $config = new Zend_Config_Ini(CONFIG_FILE);

            $loglevel = 'DEBUG';
            $class = new ReflectionClass('Zend_Log');
            $priorities = array_flip($class->getConstants());
            $loglevel = array_search($loglevel, $priorities);
            $fileFilter = new Zend_Log_Filter_Priority($loglevel, '<=');
            $fileWriter = new Zend_Log_Writer_Stream(
                APP_LOG_PATH . '/' . $aLogger . '.log');

            $fileWriter->setFormatter($simpleFormatter);
            $fileWriter->addFilter($fileFilter);
            $logger = new Zend_Log();

            $logger->addWriter($fileWriter);
            Zend_Registry::set('log' . ucfirst($aLogger), $logger);
        }
    }

    protected function _initNavigation()
    {

        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/nav.xml', 'nav');
        //var_dump($config->toArray());
        $nav = new Zend_Navigation($config);
        //var_dump($nav);
        $view->navigation($nav)->setAcl($this->_acl)
            ->setRole(Zend_Registry::get('role'));

        $navContainerConfig = new Zend_Config_Xml(
            APPLICATION_PATH . '/configs/admin-nav.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        $layout->adminMenu = $navContainer;

    }

    protected function _initRoutes()
    {

        $this->bootstrap('frontcontroller');
        $front = $this->getResource('frontcontroller');
        $router = $front->getRouter();
        // Boss context route
        $route = new Zend_Controller_Router_Route('jaunumi/', array('module' => 'default', 'action' => 'index', 'controller' => 'jaunumi'));
        $router->addRoute('jaunumi', $route);
        $route = new Zend_Controller_Router_Route('jaunums/:id', array('id' => 0, 'module' => 'default', 'action' => 'skatit', 'controller' => 'jaunumi'));
        $router->addRoute('jaunums', $route);

    }


}

