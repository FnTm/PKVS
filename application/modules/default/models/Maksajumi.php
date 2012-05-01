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
        $sql = $this->getAdapter()->select();
        $sql->from(array('m' => $this->_name), new Zend_Db_Expr("*,sum(`maksajumsValue` * ((-1)+(2 *`maksajumsCompleted`))) as balance"));
        $sql->group("maksajumsUserId");
        $userModel=new Model_Users();
        $sql->join(array("u"=>$userModel->_name),'u.userId=m.maksajumsUserId');
        return $sql;
    }

    public function getBilanceForUser($id)
    {
        return $this->fetchAll($this->select()->where("maksajumsUserId=?", $id));
    }


}
