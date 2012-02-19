<?php
/**
 * @see Sozfo_Tool_Doctrine_Provider_Abstract
 */
require_once 'Sozfo/Tool/Doctrine/Provider/Abstract.php';

/**
 * @see Zend_Tool_Framework_Provider_Pretendable
 */
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

class Sozfo_Tool_Doctrine_Provider_Doctrine
    extends Sozfo_Tool_Doctrine_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    protected $_specialties = array('Models', 'Tables');
    protected $_modelsLoaded = false;

    public function create ($module, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);

        if (false !== $this->createModels($module)) {
            $this->createTables($module);
        }
    }

    public function createModels ($module, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);
        
        if (!($modelsDirectory = self::_getModelsDirectoryResource($this->_loadedProfile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $module . '" was not found.');
        }
        if (!($schemaDirectory = self::_getSchemaDirectoryResource($this->_loadedProfile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A schema directory for module "' . $module . '" was not found.');
        }
        $options = self::_getDoctrineImportOptions($module);

        if ($this->_hasModels($modelsDirectory)) {
            $this->_modelsLoaded = true;
            
            $this->_registry->getResponse()->appendContent('Models already created. Creating again will overwrite the old files.');
            $this->_registry->getResponse()->appendContent('Use doctrine-migrations to keep track of the changes.');
            $response = $this->_registry
                             ->getClient()
                             ->promptInteractiveInput('Sure you want to overwrite the files? y/n [n]')
                             ->getContent();
                             
            if ('y' !== strtolower($response)) {
                $this->_registry->getResponse()->appendContent('Action aborted...');
                return false;
            }
        }

        // do the creation
        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would create Doctrine models from schema '  . $schemaDirectory->getContext()->getPath());
            $this->_registry->getResponse()->appendContent('Created models would be saved at '  . $modelsDirectory->getContext()->getPath());
        } else {
            $this->_registry->getResponse()->appendContent('Creating Doctrine models from schema ' . $schemaDirectory->getContext()->getPath());
            $this->_registry->getResponse()->appendContent('Save Doctrine models at' . $modelsDirectory->getContext()->getPath());
            Doctrine::generateModelsFromYaml($schemaDirectory->getContext()->getPath(), $modelsDirectory->getContext()->getPath(), $options);
        }
    }

    public function createTables ($module, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);

        if (!($modelsDirectory = self::_getModelsDirectoryResource($this->_loadedProfile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $module . '" was not found.');
        }

        // Check if tables already exists
        if (true === $this->_hasTables($modelsDirectory, $module)) {
            throw new Zend_Tool_Project_Provider_Exception('Tables already exist. Drop the tables first or alter tables by using doctrine-migrations.');
        }
        
        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would generate db tables from Doctrine models at '  . $modelsDirectory->getContext()->getPath());
        } else {
            if (false === $this->_modelsLoaded) {
                $this->_loadModels($modelsDirectory);
            }
            $this->_registry->getResponse()->appendContent('Generate db tables from Doctrine models at ' . $modelsDirectory->getContext()->getPath());
            Doctrine::createTablesFromModels($modelsDirectory->getContext()->getPath());
        }
    }

    public function load ($module, $append = false, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);

        if (!($modelsDirectory = self::_getModelsDirectoryResource($this->_loadedProfile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A module directory for module "' . $module . '" was not found.');
        }
        if (!($fixturesDirectory = self::_getFixturesDirectoryResource($this->_loadedProfile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A fixtures directory for module "' . $module . '" was not found.');
        }

        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would load data from fixtures path '  . $fixturesDirectory->getContext()->getPath());
        } else {
            $this->_registry->getResponse()->appendContent('Load data from fixtures path ' . $fixturesDirectory->getContext()->getPath());
            if (false === $this->_modelsLoaded) {
                $this->_loadModels($modelsDirectory);
            }
            Doctrine::loadData($fixturesDirectory->getContext()->getPath(), $append);
        }
    }
}