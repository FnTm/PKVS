<?php
class JP_Controller_Helper_LayoutLoader
extends Zend_Controller_Action_Helper_Abstract
{

    public function preDispatch()
    {

        $Boot = $this->getActionController()
                         ->getInvokeArg('bootstrap');
        $config = $Boot->getOptions();
        $module = $this->getRequest()->getModuleName();
        if (isset($config[$module]['resources']['layout']['layout'])) {
            $layoutScript =
                 $config[$module]['resources']['layout']['layout'];
            $this->getActionController()
                 ->getHelper('layout')
                 ->setLayout($layoutScript);
        }
    }

}

?>