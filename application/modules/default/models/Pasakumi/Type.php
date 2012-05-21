<?php
/**
 * Contains the model for the event types.
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
/**
 * Model for the event types.
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
class Model_Pasakumi_Type extends Zend_Db_Table_Abstract
{
    /**
     * Name of the DB table to use
     * @var string
     */
    public $_name = "pasakumitype";
    /**
     * Name of the Row class to which to bind the table results
     * @var string
     */
    public $_rowClass = "Model_Pasakumi_Type_Row";
    /**
     * The primary key
     * @var string
     */
    public $_primaryKey = "typeId";

    /**
     * Get all the types in descending order by primary Id
     * @param $id int the Id to exclude
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getAll($id = null)
    {
        $select = $this->select()->order($this->_primaryKey . " desc");
        //Id of an item to exclude
        if (!is_null($id)) {
            $select->where($this->_primaryKey . "!=?", $id);
        }

        return $this->fetchAll($select);
    }

    /**
     * Create a new type
     * @param $data array the data to insert
     * @return int id of the inserted type
     */
    public function createType($data)
    {
        return $this->insert($data);
    }

    /**
     * Delete a single type
     * @param $id int Id of the type to delete
     * @return int count of rows deleted
     */
    public function deleteType($id)
    {
        return $this->delete($this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
    }

    /**
     *  Get a single type by Id
     * @param $id int Id of the type
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getType($id)
    {
        return $this->fetchRow($this->select()->where('typeId=?', $id));

    }

    /**
     * Updates the record defined by the Id
     * @param $id int Id of the type to edit
     * @param $data array Data to add to the update
     * @return int Number of rows updated
     */
    public function updateType($data, $id)
    {
        return $this->update($data, $this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
    }

}
