<?php
class JP_Form_Validate_IfOther extends Zend_Validate_Abstract
{
    const OTHER_NOT_FULL = 0x5;


    public $chooseElement;
    public function __construct ($chooseElement)
    {
       $this->chooseElement=$chooseElement;



    }
    protected $_messageTemplates = array(
    self::OTHER_NOT_FULL => 'Ja izvēlaties variantu "Cits", lūdzu aizpildiet Info lauku!');

    public function isValid ($value, $context = null)
    {

        if($value==""){

            $model=new Baklans_Model_Registry_Codificator();

            if($model->IsCits($context[$this->chooseElement])){
                            $this->_error(self::OTHER_NOT_FULL);
            return false;
            }

        }
        return true;
    }

}