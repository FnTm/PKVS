<?php
/**
 * User: Janis
 * Date: 12.14.3
 * Time: 18:27
 */
 
class Model_User_Row extends Zend_Db_Table_Row_Abstract{

    public function init(){


    }
    public function getName(){
        return $this->name;
    }

}
