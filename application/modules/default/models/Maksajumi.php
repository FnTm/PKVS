<?php
/**
 *
 */
/**
 * Modelis, kurš atbildīgs par visām ar maksājumiem saistītajām datubāžu darbībām.
 */
class Model_Maksajumi extends Zend_Db_Table_Abstract
{
    public $_name = "maksajumi";

    public function createMaksajums($data)
    {
        $curdate = date("Y-m-d H:i:s");
        $data['maksajumsCreated'] = $curdate;
        if ($data['maksajumsCompleted']) {
            $data['maksajumsFinished'] = $curdate;
        }

        $this->insert($data);
    }
    public function editMaksajums($data,$id)
    {
        $curdate = date("Y-m-d H:i:s");
        //$data['maksajumsCreated'] = $curdate;
        if ($data['maksajumsCompleted']) {
            $data['maksajumsFinished'] = $curdate;
        }
        $this->update($data,$this->getAdapter()->quoteInto("maksajumsId=?",$id));
    }
    public function getMaksajums($id){
       $return=$this->fetchRow($this->select()->where("maksajumsId=?",$id));
        if(!is_null($return) && $return!==false){
            $return=$return->toArray();
        }
        return $return;
    }
    public function getMaksajumsByUser($id){
        $sql=$this->getFullQuery();
        return $this->getAdapter()->fetchAll($sql->where("maksajumsUserId=?",$id));
    }
    public function createMultiMaksajums($data){
        foreach ($data as $key => $apmekletiba) {
            if (strpos($key, "user_") !== false && $apmekletiba==1) {
                $realData=array();
                $split = explode("user_", $key);
                $userId = $split[1];
                //var_dump($id, $userId, $apmekletiba);
                $realData['maksajumsUserId']=$userId;
                $realData['maksajumsValue']=$data['maksajumsValue'];
                $realData['maksajumsTitle']=$data['maksajumsTitle'];
                $this->createMaksajums($realData);

            }

        }
    }

    public function getBilanceForAll()
    {
        return $this->getAdapter()->fetchAll($this->bilanceQuery());
    }

    private function bilanceQuery()
    {
        $sql = $this->getFullQuery(false);
        $sql->from(array('m' => $this->_name), new Zend_Db_Expr("*,sum(`maksajumsValue` * ((-1)+(1 *`maksajumsCompleted`))) as balance"));
        $sql->group("maksajumsUserId");
        return $sql;
    }
    private function getFullQuery($withFrom=true){
        $sql = $this->getAdapter()->select();
        if($withFrom){
        $sql->from(array('m' => $this->_name));
        }
        $userModel=new Model_Users();
        $sql->join(array("u"=>$userModel->_name),'u.userId=m.maksajumsUserId');
        return $sql;
    }

    public function getBilanceForUser($id)
    {
        return $this->fetchAll($this->select()->where("maksajumsUserId=?", $id));
    }


}
