<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.23.4
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba_ToKrutums extends Zend_Db_Table_Abstract
{
public $_name="apmekletibatokrutums";

    public function addBond($apmId,$krutumsId){
        $this->insert(array('apmekletibaId'=>$apmId,'krutumsId'=>$krutumsId,'dateModified'=>date('Y-m-d H:i:s')));
    }
    public function updateBond($apmId,$krutumsId){
        $where=$this->getAdapter()->quoteInto('apmekletibaId=?',$apmId);
        $where.=" and ";
        $where.=$this->getAdapter()->quoteInto('krutumsId=?',$krutumsId);
        $this->update(array('dateModified'=>date('Y-m-d H:i:s')),$where);
    }
}
