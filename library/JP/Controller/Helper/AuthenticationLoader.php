<?php
/**
 *
 * @author Janis
 * @version 
 */

/**
 * {1} Action Helper 
 * 
 * @uses actionHelper {0}
 */
class JP_Controller_Helper_AuthenticationLoader extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader
     * 
     * @return void
     */
    public function __construct(){
        // TODO Auto-generated Constructor
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }
    
 public function preDispatch()   
 {}
    /**
     * Strategy pattern: call helper as broker method
     */
    /*public function direct(){
    	        if(!Zend_Auth::getInstance()->hasIdentity()){
            $this->_redirect('authentication/login');
        }
        
        
    }
    */
}

