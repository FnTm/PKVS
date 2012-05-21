<?php
/**
 * Contains the plugin, that checks user permission
 * @author Janis Peisenieks
 * @package Authorization
 */

/**
 * Plugin that checks users permissions based on the ACL
 * @author Janis Peisenieks
 * @package Authorization
 */
class Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{

    /**
     * Contains the initialized ACL
     * @var null|Zend_Acl
     */
    private $_acl = null;

    /**
     * Initializes the plugin
     * @param Zend_Acl $acl
     */
    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
    }

    /**
     * Operates on the preDispatch callback, and checks whether the user has access to the resource
     * they are requesting
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        //We retrieve  the needed parameters from the initial request
        $module = $request->getModuleName();
        $resource = $request->getControllerName();
        $action = $request->getActionName();

        //Enable authorization only on the production machine
        if (APPLICATION_ENV == "production") {
            try {
                if (!$this->_acl->isAllowed(Zend_Registry::get('role'), $module . ':' . $resource, $action)) {
                    //$this->getActionController()->
                    /*var_dump($module);
                    var_dump($resource);
                    var_dump($action);
                    var_dump($module . ':' . $resource);
                    */
                    //Redirect the user, if the acl forbids access to the resource in question
                    $request->setControllerName('authentication')->setModuleName('default')
                        ->setActionName('login');


                }
            }
                //In case anything went wrong, lets dump the response.
            catch (Exception $ex) {
                var_dump($ex->getMessage());

            }
        }
    }

}
