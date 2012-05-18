<?php
/**
 * Model that deals with attendance relations to points
 *@author Janis Peisenieks
 *@package Default
 *@subpackage Apmekletiba
 */
class Model_Apmekletiba_ToPunkti extends Zend_Db_Table_Abstract
{

    /**
     * @var string The name of the database table to work with
     */
    public $_name="apmekletibatopunkti";

    /**
     * Adds a relation from an attendance to points
     * @param $apmId Attendance Id
     * @param $punktiId Points Id
     */
    public function addBond($apmId,$punktiId){
        $this->insert(array('apmekletibaId'=>$apmId,'punktiId'=>$punktiId,'dateModified'=>date('Y-m-d H:i:s')));
    }

    /**
     * Updates a relation form attendance to points
     * @param $apmId Attendance Id
     * @param $punktiId Points Id
     */
    public function updateBond($apmId,$punktiId){
        $where=$this->getAdapter()->quoteInto('apmekletibaId=?',$apmId);
        $where.=" and ";
        $where.=$this->getAdapter()->quoteInto('punktiId=?',$punktiId);
        $this->update(array('dateModified'=>date('Y-m-d H:i:s')),$where);
    }
}
