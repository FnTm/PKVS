<?php
/**
 *
 */
/**
 * Model responsible for actions related to punkti
 */
class Model_Punkti extends Zend_Db_Table_Abstract
{
    public $_name = "punkti";
    protected $_punkti = "punktiCombined";
    const REGISTER_EVENT = 2;
    const OTHER_EVENT = 1;
    const ATTENDANCE_EVENT=3;

    public function getAll()
    {
        return $this->getAdapter()->fetchAll($this->fullSql()->order($this->_punkti . " desc"));
    }

    public function fullSql()
    {

        $userModel = new Model_Users();
        $sql = $this->getAdapter()->select()->from($this->_name, new Zend_Db_Expr("*,SUM(`punktiValue`) as " . $this->_punkti))->join($userModel->_name, "users.userId=punkti.punktiUserId")->group("punktiUserId");
        return $sql;
    }

    public function addPunkti($userId, $eventId, $eventNote, $value)
    {
        $data = array('punktiUserId' => $userId, 'punktiEvent' => $eventId, 'punktiNote' => $eventNote,
                      "punktiValue" => $value,"punktiDate"=>date("Y-m-d H:i:s"));
       return $this->insert($data);
    }
    public function updatePunkti($punktiId,$punktiValue){
      return $this->update(array('punktiValue'=>$punktiValue),$this->getAdapter()->quoteInto("punktiId=?",$punktiId));
    }


}
