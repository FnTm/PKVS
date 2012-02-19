<?php
class JP_Form_Validate_UsernameExists extends Zend_Validate_Abstract {

    const USERNAME_EXISTS = 'usernameExists';

    protected $_messageTemplates = array(
            self::USERNAME_EXISTS => 'Username Exists'
    );

    public function isValid($value) {
        // Grab non-prototyped $context param set from Zend_Form
        $params = func_get_args();
        $context = null;
        if (isset($params[1])) {
            $context = $params[1];
        }


        $table = new Model_Users();
        $res = $table->checkUsernameExists($value);

        if($res) {
            // Exists
            $this->_error(self::USERNAME_EXISTS);
            return false;
        }

        return true;
    }
}