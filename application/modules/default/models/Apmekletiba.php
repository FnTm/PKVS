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
        $pasModel=new Model_Pasakumi();

        $fetch=$this->getAdapter()->select()->from($this->_name)->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId)->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId');
        $fetch = $this->getAdapter()->fetchRow($fetch);
        $fetch = (count($fetch == 1)) ? $fetch : null;
        if (!is_null($fetch) && $fetch!==false) {
            if ($fetch['apmekletibaTipsId'] != $apmId) {
                $krutums = $apmKrutModel->getKrutumsValue($fetch['apmekletibaTipsId'],$fetch['pasakumsCategory']);
                $krutumsValue = $krutums['krutumsValue'];
                if ($krutumsValue >= 0) {
                    $krutums = -$krutumsValue;
                }
                else {
                    $krutums = +abs($krutumsValue);
                }

                $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Mainīts apmeklējums pasākumam " . $eventId, $krutums);
                $krutums = $apmKrutModel->getKrutumsValue($apmId,$fetch['pasakumsCategory']);
                $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Mainīts apmeklējums pasākumam " . $eventId, $krutums['krutumsValue']);

                $data = array('apmekletibaTipsId' => $apmId);
                $this->update($data, "apmekletibaEventId='$eventId' and apmekletibaUserId='$userId'");
            }
        }
        else {
            $krutums = $apmKrutModel->getKrutumsValue($apmId,$fetch['pasakumsCategory']);
            $krutumsModel->addKrutums($userId, $krutumsModel::OTHER_EVENT, "Par pasākumu " . $eventId, $krutums['krutumsValue']);

            $this->insertApmeklejums($eventId, $userId, $apmId);
        }
    }
}
