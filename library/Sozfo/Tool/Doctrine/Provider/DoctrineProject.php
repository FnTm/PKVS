<?php
/**
 * @see Zend_Tool_Framework_Provider_Pretendable
 */
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

/**
 * @category   Zend
 * @package    Zend_Tool
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Sozfo_Tool_Doctrine_Provider_DoctrineProject
    extends Sozfo_Tool_Doctrine_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

    protected static $_isInitialized = false;
    protected static $_configsDirectory;

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
     * createSchemaResource will create the schemaDirectory resource at the appropriate location in the
     * profile.  NOTE: it is your job to execute the create() method on the resource, as well as
     * store the profile when done.
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createSchemaResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        if (!($moduleDirectory = self::_getModuleDirectoryResource($profile, $moduleName))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $moduleName . '" was not found.');
        }

        if (!($configsDirectory = self::_getConfigsDirectoryResource($profile, $moduleName)) instanceof Zend_Tool_Project_Profile_Resource) {
            $configsDirectory = self::createConfigsDirectoryResource($profile, $moduleName);
        }

        $schemaDirectory = $configsDirectory->createResource('schemaDirectory');

        return $schemaDirectory;
    }

    /**
     * createMigrationsResource will create the migrationsDirectory resource at the appropriate location in the
     * profile.  NOTE: it is your job to execute the create() method on the resource, as well as
     * store the profile when done.
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createMigrationsResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        if (!($moduleDirectory = self::_getModuleDirectoryResource($profile, $moduleName))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $moduleName . '" was not found.');
        }

        if (!($configsDirectory = self::_getConfigsDirectoryResource($profile, $moduleName)) instanceof Zend_Tool_Project_Profile_Resource) {
            $configsDirectory = self::createConfigsDirectoryResource($profile, $moduleName);
        }

        $migrationsDirectory = $configsDirectory->createResource('migrationsDirectory');

        return $migrationsDirectory;
    }

    /**
     * createFixturesResource will create the fixturesDirectory resource at the appropriate location in the
     * profile.  NOTE: it is your job to execute the create() method on the resource, as well as
     * store the profile when done.
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createFixturesResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        if (!($moduleDirectory = self::_getModuleDirectoryResource($profile, $moduleName))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $moduleName . '" was not found.');
        }

        if (!($configsDirectory = self::_getConfigsDirectoryResource($profile, $moduleName)) instanceof Zend_Tool_Project_Profile_Resource) {
            $configsDirectory = self::createConfigsDirectoryResource($profile, $moduleName);
        }

        $fixturesDirectory = $configsDirectory->createResource('fixturesDirectory');

        return $fixturesDirectory;
    }

    /**
     * createConfigsDirectoryResource will create the configsDirectory resource at the appropriate location in the
     * profile.  NOTE: it is your job to execute the create() method on the resource, as well as
     * store the profile when done.
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createConfigsDirectoryResource(Zend_Tool_Project_Profile $profile, $moduleName)
    {
        if (!($moduleDirectory = self::_getModuleDirectoryResource($profile, $moduleName))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $moduleName . '" was not found.');
        }

        self::$_configsDirectory = $moduleDirectory->createResource('configsDirectory');

        return self::$_configsDirectory;
    }

    /**
     * Enter description here...
     *
     * @param string $module The name of the module to create a Doctrine project in.
     */
    public function create($module)
    {
        $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

        if (self::hasResource($this->_loadedProfile, $module)) {
            throw new Zend_Tool_Project_Provider_Exception('This module already has a Doctrine project');
        }

        try {
            $schemaResource = self::createSchemaResource($this->_loadedProfile, $module);
            $migrationsResource = self::createMigrationsResource($this->_loadedProfile, $module);
            $fixturesResource = self::createFixturesResource($this->_loadedProfile, $module);
        } catch (Exception $e) {
            $response = $this->_registry->getResponse();
            $response->setException($e);
            return;
        }

        // do the creation
        if ($this->_registry->getRequest()->isPretend()) {
            if (null !== self::$_configsDirectory) {
                $this->_registry->getResponse()->appendContent('Would create a config directory at ' . self::$_configsDirectory->getContext()->getPath());
            }
            $this->_registry->getResponse()->appendContent('Would create a Doctrine project at '  . $schemaResource->getContext()->getPath());
        } else {
            if (null !== self::$_configsDirectory) {
                $this->_registry->getResponse()->appendContent('Creating a config directory at ' . self::$_configsDirectory->getContext()->getPath());
                self::$_configsDirectory->create();
            }
            $this->_registry->getResponse()->appendContent('Creating a Doctrine project at ' . $schemaResource->getContext()->getPath());
            $schemaResource->create();
            $migrationsResource->create();
            $fixturesResource->create();
            $this->_storeProfile();
        }
    }
}

