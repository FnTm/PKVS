<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/15/12
 * Time: 8:45 PM
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba_Tips extends Zend_Db_Table_Abstract
{
    public $_name = "apmekletibatipi";

    public function addApmekletibaTips($data){

    }
    public function getAll(){
        return $this->fetchAll();
    }

}
