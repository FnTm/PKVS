<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/17/12
 * Time: 1:39 AM
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba_Krutums extends Zend_Db_Table_Abstract
{
    public $_name = "atipikrutums";

    public function getKrutumsValue($tipsId){
        return $this->fetchRow($this->select()->where('apmekletibaTipsId=?',$tipsId))->toArray();
    }

}
