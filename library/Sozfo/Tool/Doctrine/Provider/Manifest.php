<?php
class Sozfo_Tool_Doctrine_Provider_Manifest implements Zend_Tool_Framework_Manifest_ProviderManifestable
{
    public function getProviders ()
    {
        require_once 'Sozfo/Tool/Doctrine/Provider/Doctrine.php';
        require_once 'Sozfo/Tool/Doctrine/Provider/DoctrineProject.php';
        require_once 'Sozfo/Tool/Doctrine/Provider/DoctrineMigrations.php';
        
        return array(
            new Sozfo_Tool_Doctrine_Provider_Doctrine,
            new Sozfo_Tool_Doctrine_Provider_DoctrineProject,
            new Sozfo_Tool_Doctrine_Provider_DoctrineMigrations,
        );
    }
}