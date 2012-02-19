<?php
class JP_Form_Validate_IfOne extends Zend_Validate_Abstract
{
    const ONE_NOT_FULL = 0x6;
    public $chooseElement;
    public function __construct ($chooseElement)
    {
        $this->chooseElement = $chooseElement;
    }
    protected $_messageTemplates = array(
    self::ONE_NOT_FULL => 'Vai nu Pasūtījuma Nr. vai Klienta Līguma Nr. vai Iebilduma Nr. vai Servisa Nr. jābūt aizpildītam');
    public function isValid ($value, $context = null)
    {
        $isEmpty = true;
        foreach ($this->chooseElement as $element) {
            if ($context[$element] != "") {
                $isEmpty = false;
            }
        }
        if ($value == "" && $isEmpty == true) {
            $this->_error(self::ONE_NOT_FULL);
            return false;
        }
        return true;
    }
}