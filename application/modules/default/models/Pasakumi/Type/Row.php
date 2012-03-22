<?php
/**
 * User: Janis
 * Date: 12.14.3
 * Time: 18:27
 */
 
class Model_Pasakumi_Type_Row extends Zend_Db_Table_Row_Abstract{

    public function init(){


    }
    public function getTitle(){
        return $this->title;
    }

}
