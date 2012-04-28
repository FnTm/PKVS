<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/17/12
 * Time: 1:39 AM
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba_Krutums extends Zend_Db_Table_Abstract
{
    public $_name = "apmekletibatipikrutums";

    public function getKrutumsValue($tipsId, $pasakumaTips)
    {
        $row=$this->fetchRow($this->select()->where('apmekletibaTipsId=?', $tipsId)->where("pasakumaTipsId=?", $pasakumaTips));
        if(!is_null($row)){
            $row=$row->toArray();
        }
        return $row;
    }

    public function createKrutumsValue($tipsId, $aTipsId, $krutumsValue)
    {
        $array = array('apmekletibaTipsId' => $aTipsId, "pasakumaTipsId" => $tipsId, "krutumsValue" => $krutumsValue);
        $this->insert($array);
    }

    public function insertKrutumsValues($tipsId, $array, $akey)
    {
        foreach ($array as $key => $val) {
            $split = explode($akey, $key);
            $val;
            if (!is_null($krutums = $this->getKrutumsValue($split[1], $tipsId))) {
                $data = array("krutumsValue" => $val);
                $this->updateKrutumsValue($krutums['atipikrutumsId'], $data);
            }
            else {
                $this->createKrutumsValue($tipsId, $split[1], $val);
            }
        }
    }

    public function updateKrutumsValue($id, $data)
    {
        $this->update($data, $this->getAdapter()->quoteInto("atipikrutumsId=?", $id));
    }

    public function getKrutumsValues($pasakumaTips, $key = null)
    {
        $return = $this->fetchAll($this->select()->where("pasakumaTipsId=?", $pasakumaTips));
        if (!is_null($key)) {
            $ret = array();
            foreach ($return as $value) {
                $ret[$key . $value->apmekletibaTipsId] = $value->krutumsValue;
            }
            $return = $ret;
        }
        else {
            $return = $return->toArray();
        }
        return $return;
    }

}
