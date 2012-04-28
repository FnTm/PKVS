<?php
class JP_Controller_Action extends Zend_Controller_Action
{
    public $_flashMessenger;
    public $elementModel;
    public $userData;
    const SUCCESS="success";
    const ERROR="error";
    public function logError($msg, $priority)
    {
        $this->view->priorityMessenger($msg, $priority);
        //throw new Exception($msg);
    }



    /**
     * Shortcut to JP_Controller_Action::logError();
     * @param string $msg
     * @param string $priority
     */
    public function log($msg, $priority)
    {
        $this->logError($msg, $priority);
    }

    /**
     * Used to include various features and avoid child classes overriding
     * init() functions.
     * @see Zend_Controller_Action::preDispatch()
     */


    public function preDispatch()
    {

        $this->userData = Zend_Auth::getInstance()->getIdentity();
    }

    public function genRandomString()
    {
        $length = 10;
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $characters = str_split($characters, 1);
        $string = "";
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, count($characters) - 1)];
        }
        return $string;
    }

    public function getStrictParam($paramName, $default, $type)
    {
        $type = "is_" . $type;
        if (!function_exists($type)) {
            throw new Exception("The variable type does not exist");
        }
        $value = parent::_getParam($paramName, $default);
        if ($value == null || $value == "") {
            throw new Exception('Id of package is not set');
        }
        if ($type != null && !$type($paramName)) {
            throw new Exception("not the right type");
        }
        return $value;
    }

   
}
