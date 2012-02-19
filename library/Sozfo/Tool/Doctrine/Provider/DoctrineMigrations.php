<?php
/**
 * @see Sozfo_Tool_Doctrine_Provider_Abstract
 */
require_once 'Sozfo/Tool/Doctrine/Provider/Abstract.php';

/**
 * @see Zend_Tool_Framework_Provider_Pretendable
 */
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

class Sozfo_Tool_Doctrine_Provider_DoctrineMigrations
    extends Sozfo_Tool_Doctrine_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    public static function showCurrentVersion (Zend_Tool_Project_Profile $profile, $module)
    {
        return self::createDoctrineMigration($profile, $module)->getCurrentVersion();
    }

    public static function showLatestVersion (Zend_Tool_Project_Profile $profile, $module)
    {
        return self::createDoctrineMigration($profile, $module)->getLatestVersion();
    }

    public static function createDiff (Zend_Tool_Project_Profile $profile, $module)
    {
        if (!($migrationsDirectory = self::_getMigrationsDirectoryResource($profile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A migrations directory for module "' . $module . '" was not found.');
        }
        if (!($schemaDirectory = self::_getSchemaDirectoryResource($profile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A schema directory for module "' . $module . '" was not found.');
        }
        if (!($modelsDirectory = self::_getModelsDirectoryResource($profile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A models directory for module "' . $module . '" was not found.');
        }

        $migrations = $migrationsDirectory->getContext()->getPath();
        $from       = $modelsDirectory->getContext()->getPath();
        $to         = $schemaDirectory->getContext()->getPath();
        Doctrine_Core::generateMigrationsFromDiff($migrations, $from, $to);
    }

    public static function createDoctrineMigration (Zend_Tool_Project_Profile $profile, $module)
    {
        if (!($migrationsDirectory = self::_getMigrationsDirectoryResource($profile, $module))) {
            throw new Zend_Tool_Project_Provider_Exception('A migrations directory for module "' . $module . '" was not found.');
        }
        $migration = new Doctrine_Migration($migrationsDirectory->getContext()->getPath());
        $migration->setTableName(strtolower($module).'_migration');
        return $migration;
    }

    public function show ($module, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);
        $currentVersion = self::showCurrentVersion($this->_loadedProfile, $module);
        $latestVersion = self::showLatestVersion($this->_loadedProfile, $module);

        if (0 === ($currentVersion)) {
            $message = 'Tables never migrated, version is ' . $currentVersion;
            if ($latestVersion > $currentVersion) {
                $message .= '. A newer version (' . $latestVersion . ') is available';
            }
            $this->_registry->getResponse()->appendContent($message);
        } elseif ($currentVersion === $latestVersion) {
            $this->_registry->getResponse()->appendContent('Tables are up to date, version is ' . $latestVersion);
        } else {
            $this->_registry->getResponse()->appendContent('Current version: ' . $currentVersion);
            $this->_registry->getResponse()->appendContent('Latest version : ' . $latestVersion);
        }
    }

    /**
     * Create a diff based on the differences between models and tables
     * @param string $module Name of module to operate in
     */
    public function create ($module, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);
        
        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would create migration files for module ' . $module);
        } else {
            $this->_registry->getResponse()->appendContent('Creating migration files for module ' . $module);
            self::createDiff($this->_loadedProfile, $module);
        }
    }

    public function migrate ($module, $version = null, $environment = null)
    {
        $this->_loadDoctrineConfig($module, $environment);

        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would migrate for module ' . $module);
            $migration = self::createDoctrineMigration($this->_loadedProfile, $module);
            if(!$migration->migrateDryRun($version)) {
                $error = current($migration->getErrors());
                throw new Zend_Tool_Project_Provider_Exception('Migration dry run failed. Reason: ' . $error->getMessage());
            }
            $this->_registry->getResponse()->appendContent('Dry run of migrations succeeded.');
        } else {
            $this->_registry->getResponse()->appendContent('Migrate for module ' . $module);
            $migration = self::createDoctrineMigration($this->_loadedProfile, $module);
            if(!$migration->migrate($version)) {
                $error = current($migration->getErrors());
                throw new Zend_Tool_Project_Provider_Exception('Migration failed. Reason: ' . $error->getMessage());
            }
            $this->_registry->getResponse()->appendContent('Migrations succeeded.');
        }
    }
}