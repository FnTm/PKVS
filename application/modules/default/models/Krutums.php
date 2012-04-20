<?php
/**
 *
 */
/**
 * Model responsible for actions related to krutums
 */
class Model_Krutums extends Zend_Db_Table_Abstract
{
    public $_name = "krutums";
    protected $_krutums = "krutumsCombined";
    const REGISTER_EVENT = 2;
    const OTHER_EVENT = 1;
    const ATTENDANCE_EVENT=3;

    public function getAll()
    {
        return $this->getAdapter()->fetchAll($this->fullSql()->order($this->_krutums . " desc"));
    }

    public function fullSql()
    {

        $userModel = new Model_Users();
        $sql = $this->getAdapter()->select()->from($this->_name, new Zend_Db_Expr("*,SUM(`krutumsValue`) as " . $this->_krutums))->join($userModel->_name, "users.userId=krutums.krutumsUserId")->group("krutumsUserId");
        return $sql;
    }

    public function addKrutums($userId, $eventId, $eventNote, $value)
    {
        $data = array('krutumsUserId' => $userId, 'krutumsEvent' => $eventId, 'krutumsNote' => $eventNote,
                      "krutumsValue" => $value,"krutumsDate"=>date("Y-m-d H:i:s"));
        $this->insert($data);
    }


}
