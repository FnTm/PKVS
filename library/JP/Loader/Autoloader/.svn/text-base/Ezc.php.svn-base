<?php

/**
 * Autoloader for ezComponents
 *
 * @author Benjamin Steininger <robo47@robo47.net>
 */
class JP_Loader_Autoloader_Ezc implements Zend_Loader_Autoloader_Interface
{
    /**
     * If the needed class/file is already loaded
     *
     * @var bool
     */
    private $_loaded = false;

    /**
     * Autoload-Method
     *
     * @param string $class name of the class
     */
    public function autoload($class)
    {
        if(!$this->_loaded) {
            require_once 'ezc/Base/src/base.php';
            $this->_loaded = true;
        }
        ezcBase::autoload($class);
    }
}