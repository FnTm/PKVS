<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/15/12
 * Time: 8:44 PM
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba extends Zend_Db_Table_Abstract
{
    public $_name = "apmekletiba";

    public function insertApmeklejums($eventId,$userId,$apmId){
        $data=array('apmekletibaUserId'=>$userId,'apmekletibaEventId'=>$eventId,'apmekletibaTipsId'=>$apmId);
        $this->insert($data);
    }
    public function getApmeklejumsByEventId($id){
       $db=$this->getAdapter();
    $userModel=new Model_Users();
        $apmTipsModel=new Model_Apmekletiba_Tips();
        $select=$db->select()->from($this->_name)->where($this->_name.".apmekletibaEventId=?",$id)
        ->join($userModel->_name,$this->_name.".apmekletibaUserId=".$userModel->_name.".userId")
        ->join($apmTipsModel->_name,$this->_name.".apmekletibaTipsId=".$apmTipsModel->_name.".apmekletibaTipsId");
        return $db->fetchAll($select);
    }
}
