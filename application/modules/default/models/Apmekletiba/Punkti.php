<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/17/12
 * Time: 1:39 AM
 * To change this template use File | Settings | File Templates.
 */
class Model_Apmekletiba_Punkti extends Zend_Db_Table_Abstract
{
    public $_name = "apmekletibatipipunkti";

    public function getPunktiValue($tipsId, $pasakumaTips)
    {
        $row=$this->fetchRow($this->select()->where('apmekletibaTipsId=?', $tipsId)->where("pasakumaTipsId=?", $pasakumaTips));
        if(!is_null($row)){
            $row=$row->toArray();
        }
        return $row;
    }

    public function createPunktiValue($tipsId, $aTipsId, $punktiValue)
    {
        $array = array('apmekletibaTipsId' => $aTipsId, "pasakumaTipsId" => $tipsId, "punktiValue" => $punktiValue);
        $this->insert($array);
    }

    public function insertPunktiValues($tipsId, $array, $akey)
    {
        foreach ($array as $key => $val) {
            $split = explode($akey, $key);
            $val;
            if (!is_null($punkti = $this->getPunktiValue($split[1], $tipsId))) {
                $data = array("punktiValue" => $val);
                $this->updatePunktiValue($punkti['atipipunktiId'], $data);
            }
            else {
                $this->createPunktiValue($tipsId, $split[1], $val);
            }
        }
    }

    public function updatePunktiValue($id, $data)
    {
        $this->update($data, $this->getAdapter()->quoteInto("atipipunktiId=?", $id));
    }

    public function getPunktiValues($pasakumaTips, $key = null)
    {
        $return = $this->fetchAll($this->select()->where("pasakumaTipsId=?", $pasakumaTips));
        if (!is_null($key)) {
            $ret = array();
            foreach ($return as $value) {
                $ret[$key . $value->apmekletibaTipsId] = $value->punktiValue;
            }
            $return = $ret;
        }
        else {
            $return = $return->toArray();
        }
        return $return;
    }

}
