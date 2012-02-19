<?php
class Helpdesk_Controller_Action_Helper_ModelLoader
extends Zend_Controller_Action_Helper_Abstract
{
        public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
        {
                set_include_path(
                        get_include_path() . PATH_SEPARATOR .
                        APPLICATION_PATH.'modules\\'.$request->getModuleName().'\models'
                );
        }
}

?>
