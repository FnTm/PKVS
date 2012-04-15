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
    protected $_krutums="krutumsCombined";

    public function getAll()
    {
        return $this->getAdapter()->fetchAll($this->fullSql());
    }

    public function fullSql()
    {

        $userModel = new Model_Users();
        $sql = $this->getAdapter()->select()->from($this->_name,new Zend_Db_Expr("*,SUM(`krutumsValue`) as ".$this->_krutums))->join($userModel->_name,"users.userId=krutums.krutumsUserId");
        return $sql;
    }


}
