<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initAdminNavigation(){
    $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $navContainerConfig = new Zend_Config_Xml(
        APPLICATION_PATH . '/configs/admin-nav.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        $view->navigation($navContainer)
            ->setAcl($this->_acl)
            ->setRole(Zend_Registry::get('role'));
    }
}
