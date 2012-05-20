<?php
/**
 * Model for managing all data transactions for galleries
 * @author Janis Peisenieks
 * @package Galerijas
 * @subpackage Default
 */
class Model_Galerijas extends Zend_Db_Table_Abstract
{

    /**
     * Name of the DB table to use
     * @var string
     */
    public $_name = "galleries";
    /**
     * Name of primary key
     * @var string
     */
    public $_primaryKey = "galleryId";

    /** Creates a single gallery
     * @param $data array of data to insert
     * @return mixed ID of gallery
     */
    public function createGallery($data)
    {
        return $this->insert($data);
    }

    /** Updates a single gallery
     * @param $id int the Id of the gallery to update
     * @param $data array Data to update
     */
    public function updateGallery($id, $data)
    {


        //Quote the id, and update the row with the array of data
        $return = $this->update($data, $this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
        return $return;
    }

    /** Fetches a single gallery
     * @param $id int The id of the gallery to get
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getGallery($id)
    {
        //Try to get the row with the specified id
        $return = $this->fetchRow($this->select()->where($this->_primaryKey . "=?", $id));
        //If the row is found, transform to array;
        if (!is_null($return) && $return !== false) {
            $return = $return->toArray();
        }
        return $return;
    }

    /** Get ALL the galleries!
     * @return array
     */
    public function getAll()
    {
        return $this->fetchAll($this->select()->order($this->_primaryKey . " desc"))->toArray();
    }

    /**
     * Get ALL the galleries with at least one image
     * @return array|null
     */
    public function getAllWithOneImage()
    {
        //Build a multi-table select object
        $select = $this->getAdapter()->select();
        $select->from(array('g' => $this->_name))
            ->joinLeft(array("p" => 'pictures'), 'g.galleryId=p.galleryId')
            ->where("p.pictureId IS NOT NULL")->group("g.galleryId");
        return $this->getAdapter()->fetchAll($select);
    }

    /** Deletes a selected gallery
     * @param $id int the Id of the gallery
     * @return int
     */
    public function deleteGallery($id)
    {
        return $this->delete($this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
    }
}
