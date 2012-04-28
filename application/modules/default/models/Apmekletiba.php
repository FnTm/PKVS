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
      return  $this->insert($data);
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
        $apmToKrutumsModel = new Model_Apmekletiba_ToKrutums();
        $pasModel = new Model_Pasakumi();

        /*$fetch=$this->getAdapter()->select()->from($this->_name)->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId)->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId');
     */
        /*Savienojam apmeklējuma un krutuma tabulas, pievienojam meklēšanu pēc
        lietotājaId un pasākumaId */
        $fetch = $this->getAdapter()->select()->from($this->_name)
            ->join($apmToKrutumsModel->_name, $apmToKrutumsModel->_name . '.apmekletibaId=' . $this->_name . '.apmekletibaId')
            ->join($krutumsModel->_name, $krutumsModel->_name . '.krutumsId=' . $apmToKrutumsModel->_name . '.krutumsId')
            ->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId')
            ->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId);
        //var_dump($fetch->assemble());

        $fetch = $this->getAdapter()->fetchRow($fetch); //Paņemam vienu rindu no vaicājuma rezultātiem

        //Pārbaudām vai iegūtā vērtībā ir Rinda vai null;
        $fetch = (count($fetch == 1)) ? $fetch : null;

        if (!is_null($fetch) && $fetch !== false) {

            $krutums = $apmKrutModel->getKrutumsValue($apmId, $fetch['pasakumsCategory']);
            $krutumsModel->updateKrutums($fetch['krutumsId'], $krutums['krutumsValue']);

            $data = array('apmekletibaTipsId' => $apmId);
            $this->update($data, $this->getAdapter()->quoteInto("apmekletibaId=?", $fetch['apmekletibaId']));
            $apmToKrutumsModel->updateBond($fetch['apmekletibaId'],$fetch['krutumsId']);
        }
        else {
            $fetch=$pasModel->getPasakums($eventId);

            if(!is_array($fetch)){
                $fetch=$fetch->toArray();
            }

            $krutums = $apmKrutModel->getKrutumsValue($apmId, $fetch['pasakumsCategory']);
           /* var_dump($krutums);
            exit;*/
           $krutumsId= $krutumsModel->addKrutums($userId, $krutumsModel::ATTENDANCE_EVENT, "Par pasākumu " . $eventId, $krutums['krutumsValue']);

           $apId= $this->insertApmeklejums($eventId, $userId, $apmId);
            $apmToKrutumsModel->addBond($apId,$krutumsId);
        }
    }
}
