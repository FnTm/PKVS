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
    public $punktiModel;

    public function init()
    {
        $this->punktiModel = new Model_Punkti();
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
    public function getApmeklejumsByUser($id){
        $db = $this->getAdapter();
        $userModel = new Model_Users();
        $apmTipsModel = new Model_Apmekletiba_Tips();
        $pasModel = new Model_Pasakumi();
        $select = $db->select()->from($this->_name)->where($userModel->_name . ".userId=?", $id)
            ->join($userModel->_name, $this->_name . ".apmekletibaUserId=" . $userModel->_name . ".userId")
            ->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId')
            ->join($apmTipsModel->_name, $this->_name . ".apmekletibaTipsId=" . $apmTipsModel->_name . ".apmekletibaTipsId");
        $return = $db->fetchAll($select);

        return $return;
    }
    public function updateApmeklejums($eventId, $userId, $apmId)
    {

        $punktiModel = new Model_Punkti();
        $apmKrutModel = new Model_Apmekletiba_Punkti();
        $apmToPunktiModel = new Model_Apmekletiba_ToPunkti();
        $pasModel = new Model_Pasakumi();

        /*$fetch=$this->getAdapter()->select()->from($this->_name)->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId)->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId');
     */
        /*Savienojam apmeklējuma un krutuma tabulas, pievienojam meklēšanu pēc
        lietotājaId un pasākumaId */
        $fetch = $this->getAdapter()->select()->from($this->_name)
            ->join($apmToPunktiModel->_name, $apmToPunktiModel->_name . '.apmekletibaId=' . $this->_name . '.apmekletibaId')
            ->join($punktiModel->_name, $punktiModel->_name . '.punktiId=' . $apmToPunktiModel->_name . '.punktiId')
            ->join($pasModel->_name,$pasModel->_name.'.'.$pasModel->_primary.'='.$this->_name.'.apmekletibaEventId')
            ->where("apmekletibaUserId=?", $userId)
            ->where('apmekletibaEventId=?', $eventId);
        //var_dump($fetch->assemble());

        $fetch = $this->getAdapter()->fetchRow($fetch); //Paņemam vienu rindu no vaicājuma rezultātiem

        //Pārbaudām vai iegūtā vērtībā ir Rinda vai null;
        $fetch = (count($fetch == 1)) ? $fetch : null;

        if (!is_null($fetch) && $fetch !== false) {

            $punkti = $apmKrutModel->getPunktiValue($apmId, $fetch['pasakumsCategory']);
            $punktiModel->updatePunkti($fetch['punktiId'], $punkti['punktiValue']);

            $data = array('apmekletibaTipsId' => $apmId);
            $this->update($data, $this->getAdapter()->quoteInto("apmekletibaId=?", $fetch['apmekletibaId']));
            $apmToPunktiModel->updateBond($fetch['apmekletibaId'],$fetch['punktiId']);
        }
        else {
            $fetch=$pasModel->getPasakums($eventId);

            if(!is_array($fetch)){
                $fetch=$fetch->toArray();
            }

            $punkti = $apmKrutModel->getPunktiValue($apmId, $fetch['pasakumsCategory']);
           /* var_dump($punkti);
            exit;*/
           $punktiId= $punktiModel->addPunkti($userId, $punktiModel::ATTENDANCE_EVENT, "Par pasākumu " . $eventId, $punkti['punktiValue']);

           $apId= $this->insertApmeklejums($eventId, $userId, $apmId);
            $apmToPunktiModel->addBond($apId,$punktiId);
        }
    }
}
