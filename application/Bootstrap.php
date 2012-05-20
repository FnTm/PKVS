<?php
/** Contains the main Bootstrap
 * @author Janis Peisenieks
 * @package Bootstrap
 */
/** Main project bootstrapping class
 * @author Janis Peisenieks
 * @package Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * ACL model
     * @var Model_AuthAcl
     */
    private $_acl = null;

    /**
     * Sets up module autoloading
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $modelLoader = new Zend_Application_Module_Autoloader(
            array('namespace' => '',
                'basePath' => APPLICATION_PATH . '/modules/default'));
        //Autoload PhpThumb library
        Zend_Loader_Autoloader::getInstance()->pushAutoloader(new JP_Loader_Autoloader_PhpThumb());
        return $modelLoader;
    }

    /**
     * Sets up draugiem.lv configuration options
     */
    protected function _initloadDraugiemConfig()
    {
        //Retrieve the draugiem.lv config from a file
        $draugiemConf = new Zend_Config_Ini(APPLICATION_PATH . '/configs/draugiem.config.ini', APPLICATION_ENV);
        //Save it to global registry
        Zend_Registry::set("draugiemOptions", $draugiemConf->draugiem);
        /** @var $draugiem Zend_Config_Ini */
        /*$draugiem=Zend_Registry::get("draugiemOptions");
       var_dump($draugiem->secret);
        */
    }

    /**
     * Initializes ACL and the related plugins
     */
    protected function _initACL()
    {
        //We determine the users role and save it to registry
        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Registry::set('role',
                Zend_Auth::getInstance()->getStorage()->read()->role);
        } else {
            Zend_Registry::set('role', 'guest');
        }

        $this->_acl = new Model_AuthAcl();
        //Get the Front controller instance, and register our access checking plugin
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Plugin_AccessCheck($this->_acl));
    }

    /**
     * Initializes various view helpers as well as page-level elements
     */
    protected function _initViewHelpers()
    {
        //We make sure, that the layout is bootstrapped, since we need to modify it.
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        //We set up the view helpers, to enable jQuery and some other plugins
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

    /**
     * Class autoloader initialization
     * @return Bootstrap
     */
    protected function _initAutoloaders()
    {
        //We add classes with JP_ prefix to autoloader
        $this->getApplication()->setAutoloaderNamespaces(
            array('JP_'));
        return $this;
    }

    /**
     * jQuery initialization
     * @return Zend_View
     */
    protected function _initJqueryLoad()
    {
        $view = new Zend_View();
        $view->addHelperPath("ZendX/JQuery/View/Helper",
            "ZendX_JQuery_View_Helper");
        //We set up the jQuery stylesheet and paths
        $view->jQuery()
            ->addStylesheet(
            '/js/jquery/css/ui-lightness/jquery-ui-1.7.2.custom.css')
            ->setLocalPath('/js/jquery/jquery.php')
            ->setUiLocalPath('/js/jquery-ui-1.8.16.custom.min.php');
        //We enable jQuery by default
        $view->jQuery()->enable();
        ZendX_JQuery::enableView($view);
        return $view;
    }

    /**
     * Register a plugin, that autoloads a layout for each module
     */
    protected function _initLayoutHelper()
    {
        $this->bootstrap('frontController');
        $layout = Zend_Controller_Action_HelperBroker::addHelper(
            new JP_Controller_Helper_LayoutLoader());
    }

    /**
     * We initialize 2 types of data caches: a short one, and a long one.
     */
    protected function _initDataCache()
    {
        //Initialize parameter for the long cache
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
        //Initialize parameter for the short cache
        $frontend = array("lifetime" => 720, // cache lifetime of 10 hours (time is in seconds)
            "automatic_serialization" => true); //default is false
        $short_cache = Zend_Cache::factory("Core", "File", $frontend,
            $backend);
        Zend_Registry::set('shortcache', $short_cache);
        //Set the default metadata cache to the long one.
        Zend_Db_Table_Abstract::setDefaultMetadataCache($zend_cache);
    }


    /**
     * Initializing the Database
     */
    protected function _initDb()
    {
        //Registering a plugin, that allows for multiple databases to be present in one system
        $resource = $this->getPluginResource('multidb');
        Zend_Registry::set("multidb", $resource);
    }

    /**
     * Initializing a couple of loggers
     */
    protected function _initLoggers()
    {
        $loggers = array('framework', 'database', 'import', 'admin', 'login',
            'minifeed', 'signoff', 'notification', 'cron', 'newsletter', 'mail',
            'upload', 'application', 'pdf', 'export');
        Zend_Registry::set('loggers', $loggers);
        //We set up the loggin format
        $logFormat = date('r') . ', "%message%" (%priorityName%)' . PHP_EOL;
        $simpleFormatter = new Zend_Log_Formatter_Simple($logFormat);
        foreach ($loggers as $aLogger) {
            $config = new Zend_Config_Ini(CONFIG_FILE);
            // Set up the level at which to start logging
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
            //Adding the writers to the logger, to enable writing
            $logger->addWriter($fileWriter);
            //Registering each logger in the registry
            Zend_Registry::set('log' . ucfirst($aLogger), $logger);
        }
    }

    /**
     * Initializing navigation
     */
    protected function _initNavigation()
    {
        //bootstrapping the layout
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        //Configuring the frontend menu
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/nav.xml', 'nav');
        //var_dump($config->toArray());
        $nav = new Zend_Navigation($config);
        $view->navigation($nav)->setAcl($this->_acl)
            ->setRole(Zend_Registry::get('role'));

        //configuring the admin menu
        $navContainerConfig = new Zend_Config_Xml(
            APPLICATION_PATH . '/configs/admin-nav.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        $layout->adminMenu = $navContainer;

    }

    /**
     * Initializing routes
     */
    protected function _initRoutes()
    {
        //We make sure front controller is initializes
        $this->bootstrap('frontcontroller');
        $front = $this->getResource('frontcontroller');
        $router = $front->getRouter();

        //We set up a couple of new, short, routes
        $route = new Zend_Controller_Router_Route('jaunumi/', array('module' => 'default', 'action' => 'index', 'controller' => 'jaunumi'));
        $router->addRoute('jaunumi', $route);
        $route = new Zend_Controller_Router_Route('jaunums/:id', array('id' => 0, 'module' => 'default', 'action' => 'skatit', 'controller' => 'jaunumi'));
        $router->addRoute('jaunums', $route);

    }

    /**
     * Initialize form error message translation
     */
    protected function _initTranslations(){
        $translator = new Zend_Translate(
            array(
                'adapter' => 'array',
                'content' => APPLICATION_PATH . '/../resources/languages',
                'locale'  => 'lv',
                'scan' => Zend_Translate::LOCALE_DIRECTORY
            )
        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }


}

