<?php
/**
 * @see Zend_Tool_Project_Provider_Abstract
 */
require_once 'Zend/Tool/Project/Provider/Abstract.php';

/**
 * @see Zend_Tool_Framework_Registry
 */
require_once 'Zend/Tool/Framework/Registry.php';

/**
 * @see Zend_Tool_Project_Provider_View
 */
require_once 'Zend/Tool/Project/Provider/View.php';

/**
 * @see Zend_Tool_Project_Provider_Exception
 */
require_once 'Zend/Tool/Project/Provider/Exception.php';

/**
 * @category   Zend
 * @package    Zend_Tool
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Sozfo_Tool_Doctrine_Provider_Abstract extends Zend_Tool_Project_Provider_Abstract
{
    protected static $_config;
    protected static $_environment = 'development';
    protected static $_paths = array();
    protected static $_import;
    
    public function __construct ()
    {
        if (!self::$_isInitialized) {
            $contextRegistry = Zend_Tool_Project_Context_Repository::getInstance();
            $contextRegistry->addContextsFromDirectory(
                dirname(dirname(__FILE__)) . '/Context/', 'Sozfo_Tool_Doctrine_Context_'
            );
        }
        
        parent::__construct();
    }

    /**
     * hasResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $controllerName
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function hasResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        if (!is_string($moduleName)) {
            throw new Zend_Tool_Project_Provider_Exception('Sozfo_Tool_Doctrine_Provider_DoctrineProject::hasResource() expects \"moduleName\" is an existing module resource.');
        }

        $moduleDirectory = self::_getModuleDirectoryResource($profile, $moduleName);
        if (!($moduleDirectory instanceof Zend_Tool_Project_Profile_Resource)) {
            throw new Zend_Tool_Project_Provider_Exception('Module name ' . $moduleName . ' not found!');
        }
        return (($moduleDirectory->search(array('configsDirectory', 'schemaDirectory'))) instanceof Zend_Tool_Project_Profile_Resource);
    }

    protected static function _getApplicationConfigsFileResource (Zend_Tool_Project_Profile $profile)
    {
        $profileSearchParams = array('applicationDirectory', 'configsDirectory', 'applicationConfigFile');
        return $profile->search($profileSearchParams);
    }

    /**
     * _getModuleDirectoryResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    protected static function _getModuleDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName));
        return $profile->search($profileSearchParams);
    }

    protected static function _getConfigsDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'configsDirectory');
        return $profile->search($profileSearchParams);
    }

    protected static function _getModelsDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'modelsDirectory');
        return $profile->search($profileSearchParams);
    }

    protected static function _getSchemaDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'configsDirectory', 'schemaDirectory');
        return $profile->search($profileSearchParams);
    }

    protected static function _getMigrationsDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'configsDirectory', 'migrationsDirectory');
        return $profile->search($profileSearchParams);
    }

    protected static function _getFixturesDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'configsDirectory', 'fixturesDirectory');
        return $profile->search($profileSearchParams);
    }

    protected static function _setEnvironment ($environment)
    {
        self::$_environment = (string) $environment;
    }

    protected static function _getEnvironment ()
    {
        return self::$_environment;
    }

    protected static function _getDoctrineImportOptions ($module)
    {
        $options = self::$_import;

        if (isset($options['classPrefix'])) {
            $options['classPrefix'] = ucfirst(strtolower($module)) . '_' . $options['classPrefix'];
        }
        return $options;
    }

    protected function _loadModels (Zend_Tool_Project_Profile_Resource $modelsDirectory)
    {
        require_once realpath($modelsDirectory->getContext()->getPath() . '/../../default/models/Doctrine/Abstract.php');
        
        $models = array();
        $iterator = new DirectoryIterator($modelsDirectory->getContext()->getPath());
        foreach ($iterator as $file) {
            if ($file->isDir() && !$file->isDot()) {
                if ($this->_registry->getRequest()->isVerbose()) {
                    $this->_registry->getResponse()->appendContent('Loading models from ' . $file->getPathname());
                }
                $models = array_merge(Doctrine_Core::loadModels($file->getPathname()), $models);
            }
        }
        if ($this->_registry->getRequest()->isVerbose()) {
            $this->_registry->getResponse()->appendContent('Loading models from ' . $modelsDirectory->getContext()->getPath());
        }
        return array_merge(Doctrine_Core::loadModels($modelsDirectory->getContext()->getPath(), $models));
    }

    protected function _hasModels (Zend_Tool_Project_Profile_Resource $modelsDirectory)
    {
        return (count($this->_loadModels($modelsDirectory)) > 0);
    }

    protected function _hasTables (Zend_Tool_Project_Profile_Resource $modelsDirectory, $module)
    {
        $iterator = new DirectoryIterator($modelsDirectory->getContext()->getPath());
        foreach ($iterator as $file) {
            if ($file->isFile() && !$file->isDot()) {
                break;
            }
        }
        if (is_dir($file->getPath() . '/Base')) {
            require_once($file->getPath() . '/Base/' . $file->getFilename());
        }
        if (!file_exists($file->getPathname())) {
            throw new Zend_Tool_Project_Provider_Exception('No models exist at ' . $modelsDirectory->getContext()->getPath());
        }
        require_once($file->getPathname());
        
        $name  = ucfirst(strtolower($module)) . '_Model_' . substr($file->getFilename(), 0, strrpos($file->getFilename(), '.'));
        $model = new $name;
        $table  = $model->getTable()->getTableName();
        unset($model);
        
        return Doctrine_Manager::connection()->import->tableExists($table);
    }

    protected function _loadDoctrineConfig ($module, $environment = null)
    {
        if (null === self::$_config) {
            $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);
            if (null !== $environment) {
                self::_setEnvironment($environment);
            }

            if (!self::hasResource($this->_loadedProfile, $module)) {
                throw new Zend_Tool_Project_Provider_Exception('The module ' . $module . ' does not have a Doctrine project');
            }
            if(!($resource = self::_getApplicationConfigsFileResource($this->_loadedProfile))) {
                throw new Zend_Tool_Project_Provider_Exception('No application configuration file was found');
            }

            switch ($resource->getAttribute('type')) {
                case 'ini':
                    require_once 'Zend/Config/Ini.php';
                    $config = new Zend_Config_Ini($resource->getContext()->getPath(), self::_getEnvironment());
                    break;
                case 'xml':
                    require_once 'Zend/Config/Xml.php';
                    $config = new Zend_Config_Xml($resource->getContext()->getPath(), self::_getEnvironment());
                    break;
                default:
                    throw new Zend_Tool_Project_Provider_Exception('Type of configuration file not supported');
            }

            if (!isset($config->resources->doctrine)) {
                throw new Zend_Tool_Project_Provider_Exception('No doctrine resource found in configuration file');
            }

            if (isset($config->resources->doctrine->iniPath)) {
                self::$_config = new Zend_Config_Ini($config->resources->doctrine->iniPath, self::_getEnvironment());
            } else {
                self::$_config = $config->resources->doctrine;
            }

            foreach (self::$_config->toArray() as $type => $options) {
                switch ($type) {
                    case 'autoload':
                        self::_initDoctrineAutoload();
                        break;
                    case 'connection':
                    case 'connections':
                        self::_initDoctrineConnections();
                        break;
                    case 'paths':
                        self::_initDoctrinePaths();
                        break;
                    case 'manager':
                        self::_initDoctrineManager();
                        break;
                    case 'import':
                        self::_initDoctrineImport();
                        break;
                }
            }
        }
    }

    protected static function _initDoctrineAutoload ()
    {
        if ('1' === self::$_config->autoload) {
            require_once 'Zend/Loader/Autoloader.php';
            Zend_Loader_Autoloader::getInstance()
                                  ->registerNamespace('Doctrine')
                                  ->pushAutoloader(array('Doctrine', 'autoload'));
            spl_autoload_register(array('Doctrine', 'modelsAutoload'));
        }
    }

    protected static function _initDoctrineConnections ()
    {
        if (isset(self::$_config->connection)) {
            $connections = array(self::$_config->connection->toArray());
        } elseif (isset(self::$_config->connections)) {
            $connections = self::$_config->connections->toArray();
        } else {
            throw new Zend_Tool_Project_Provider_Exception('No connection information provided');
        }

        foreach ($connections as $name => $options) {
            if (!is_array($options)) {
                throw new Zend_Tool_Project_Provider_Exception('Invalid connection on ' . $name);
            }

            if (!array_key_exists('dsn', $options)) {
                throw new Zend_Tool_Project_Provider_Exception('Undefined DSN on ' . $name);
            }

            if (empty($options['dsn'])) {
                throw new Zend_Tool_Project_Provider_Exception('Invalid DSN on ' . $name);
            }

            $dsn = (is_array($options['dsn']))
                 ? self::_buildDsnFromArray($options['dsn'])
                 : $options['dsn'];

            $conn = Doctrine_Manager::connection($dsn, $name);

            if (array_key_exists('attributes', $options)) {
                self::_setAttributes($conn, $options['attributes']);
            }
        }
    }

    protected static function _initDoctrinePaths ()
    {
        $options = array_change_key_case(self::$_config->paths->toArray(), CASE_LOWER);

        foreach ($options as $key => $value) {
            if (!is_array($value)) {
                throw new Zend_Tool_Project_Provider_Exception("Invalid paths on $key.");
            }

            self::$_paths[$key] = array();

            foreach ($value as $subKey => $subVal) {
                if (!empty($subVal)) {
                    if ($key === 'modules') {
                        $path = $subVal;
                    } else {
                        $path = realpath($subVal);

                        if (!is_dir($path)) {
                            throw new Zend_Tool_Project_Provider_Exception("$subVal does not exist.");
                        }
                    }

                    self::$_paths[$key][$subKey] = $path;
                }
            }
        }
    }

    protected static function _initDoctrineManager ()
    {
        $options = array_change_key_case(self::$_config->manager->toArray(), CASE_LOWER);

        if (array_key_exists('attributes', $options)) {
            $this->_setAttributes(
                Doctrine_Manager::getInstance(),
                $options['attributes']
            );
        }
    }

    protected static function _initDoctrineImport()
    {
        self::$_import = self::$_config->import->toArray();
        
        if (isset(self::$_import['baseClassesDirectory']) && self::$_import['baseClassesDirectory'] === '') {
            self::$_import['baseClassesDirectory'] = null;
        }
    }

    /**
     * Build DSN string from an array
     *
     * @param   array $dsn
     * @return  string
     */
    protected static function _buildDsnFromArray(array $dsn)
    {
        $options = null;
        if (array_key_exists('options', $dsn)) {
            $options = http_build_query($dsn['options']);
        }

        return sprintf('%s://%s:%s@%s/%s?%s',
            $dsn['adapter'],
            $dsn['user'],
            $dsn['pass'],
            $dsn['hostspec'],
            $dsn['database'],
            $options);
    }

    /**
     * Set attributes for a Doctrine_Configurable instance
     *
     * @param   Doctrine_Configurable $object
     * @param   array $attributes
     * @return  void
     * @throws  Zend_Application_Resource_Exception
     */
    protected static function _setAttributes(Doctrine_Configurable $object, array $attributes)
    {
        $reflect = new ReflectionClass('Doctrine');
        $doctrineConstants = $reflect->getConstants();

        $attributes = array_change_key_case($attributes, CASE_UPPER);

        foreach ($attributes as $key => $value) {
            if (!array_key_exists($key, $doctrineConstants)) {
                throw new Zend_Tool_Project_Provider_Exception("Invalid attribute $key.");
            }

            $attrIdx = $doctrineConstants[$key];

            if (is_string($value)) {
                $value = strtoupper($value);
                if (array_key_exists($value, $doctrineConstants)) {
                    $attrVal = $doctrineConstants[$value];
                    $object->setAttribute($attrIdx, $attrVal);
                }
            }
        }
    }
}