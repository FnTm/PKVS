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
    public $krutumsModel;

    public function init()
    {
        $this->krutumsModel = new Model_Krutums();
    }

    public function insertApmeklejums($eventId, $userId, $apmId)
    {
        $data = array('apmekletibaUserId' => $userId, 'apmekletibaEventId' => $eventId, 'apmekletibaTipsId' => $apmId);
        $this->insert($data);
    }

    public function getApmeklejumsByEventId($id, $userIdAsKey = false)
    {
        $db = $this->getAdapter();
        $userModel = new Model_Users();
        $apmTipsModel = new Model_Apmekletiba_Tips();
        $select = $db->select()->from($this->_name)->where($this->_name . ".apmekletibaEventId=?", $id)
            ->join($userModel->_name, $this->_name . ".apmekletibaUserId=" . $userModel->_name . ".userId")
            ->join($apmTipsModel->_name, $this->_name . ".apmekletibaTipsId=" . $apmTipsModel->_name . ".apmekletibaTipsId");
        $return = $db->fetchAll($select);
        if ($userIdAsKey) {
            $r = array();
            foreach ($return as $array) {
                $r[$array['userId']] = $array;
            }
            return $r;
        }
        return $return;
    }

    public function updateApmeklejums($eventId, $userId, $apmId)
    {

        $krutumsModel = new Model_Krutums();
        $apmKrutModel = new Model_Apmekletiba_Krutums();
        var_dump(func_get_args());
        $fetch = $this->fetchRow($this->select()->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId));
        var_dump((!is_null($fetch)));
        if (!is_null($fetch)) {
            if ($fetch->apmekletibaTipsId != $apmId) {
                $krutums = $apmKrutModel->getKrutumsValue($fetch->apmekletibaTipsId);
                $krutumsValue = $krutums['krutumsValue'];
                if ($krutumsValue >= 0) {
                    $krutums = -$krutumsValue;
                }
                else {
                    $krutums = +abs($krutumsValue);
                }

                $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Mainīts apmeklējums pasākumam " . $eventId, $krutums);
                $krutums = $apmKrutModel->getKrutumsValue($apmId);
                $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Mainīts apmeklējums pasākumam " . $eventId, $krutums['krutumsValue']);

                $data = array('apmekletibaTipsId' => $apmId);
                $this->update($data, "apmekletibaEventId='$eventId' and apmekletibaUserId='$userId'");
            }
        }
        else {
            $krutums = $apmKrutModel->getKrutumsValue($apmId);
            $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Par pasākumu " . $eventId, $krutums['krutumsValue']);

            $this->insertApmeklejums($eventId, $userId, $apmId);
        }
    }
}
