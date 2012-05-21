<?php
/** Contains event Model class
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Default
 */
/**
 * A model for event management
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Default
 */

class Model_Pasakumi extends Zend_Db_Table_Abstract
{
    /**
     * The name of the DB table to use
     * @var string
     */
    public $_name = "pasakumi";
    /**
     * Name of the Row class to bind the results to
     * @var string
     */
    public $_rowClass = "Model_Pasakumi_Row";
    /**
     * The primary Id
     * @var string
     */
    public $_primary = 'pasakumsId';

    /**
     * Event creation
     * @param $data array The data to insert
     * @return mixed The primary key, if successful insert
     */
    public function createPasakums($data)
    {
        return $this->insert($data);

    }

    /**
     * Retrieve all events ordered by the id
     * @return array
     */
    public function getAllPasakumi()
    {
        return $this->getAdapter()->fetchAll($this->getAdapter()->select()->from($this->_name)->order("pasakumsId desc"));
    }

    /**
     * Retrieve all events ordered by nearness to today
     * @return array
     */
    public function getClosestPasakumi()
    {

        return $this->getAdapter()->fetchAll($this->getAdapter()->select()->from($this->_name, new Zend_Db_Expr('* , ABS( UNIX_TIMESTAMP(  `pasakumsTime` ) - UNIX_TIMESTAMP( NOW( ) ) ) AS diff'))->order('diff asc'));
    }


    /**
     * Delete a specified event
     * @param $id int Events Id
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getPasakums($id)
    {

        return $this->fetchRow($this->select()->where("pasakumsId=?", $id));
    }


    /**
     * Update the selected event with the provided data
     * @param $id int Id of the event to edit
     * @param $data array the data to insert
     * @return int number of rows affected
     */
    public function updatePasakums($id, $data)
    {
        $where = $this->getAdapter()->quoteInto('pasakumsId = ?', $id);
        return $this->update($data, $where);
    }

    /**
     * Event deletion function
     * @param $id int the Id of the event to delete
     * @return int
     */
    public function deletePasakums($id)
    {
        $adapter = $this->getAdapter();
        return $this->delete($adapter->quoteInto("pasakumsId=?", $id));
    }


}
