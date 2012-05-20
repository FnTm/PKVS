<?php
/**
 * Model for managing all data transactions for pictures
 * @author Janis Peisenieks
 * @package Bildes
 * @subpackage Default
 */
class Model_Bildes extends Zend_Db_Table_Abstract
{

    /**
     * Name of the DB table to use
     * @var string
     */
    public $_name = "pictures";
    /**
     * Name of primary key
     * @var string
     */
    public $_primaryKey = "pictureId";

    /** Creates a single picture
     * @param $data array of data to insert
     * @return mixed ID of picture
     */
    public function createPicture($data)
    {
        return $this->insert($data);
    }

    /** Updates a single picture
     * @param $id int the Id of the picture to update
     * @param $data array Data to update
     * @return int number of pictures updated
     */
    public function updatePicture($id, $data)
    {
        //Quote the id, and update the row with the array of data
        $return = $this->update($data, $this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
        return $return;
    }

    /** Fetches a single picture
     * @param $id int The id of the picture to get
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getPicture($id)
    {
        //Try to get the row with the specified id
        $return = $this->fetchRow($this->select()->where($this->_primaryKey . "=?", $id));
        //If the row is found, transform to array;
        if (!is_null($return) && $return !== false) {
            $return = $return->toArray();
        }
        return $return;
    }

    /** Get ALL the pictures!
     * @return array
     */
    public function getAll()
    {
        return $this->fetchAll($this->select()->order($this->_primaryKey . " desc"))->toArray();
    }

    /** Deletes a selected picture
     * @param $id int the Id of the picture
     * @return int
     */
    public function deletePicture($id)
    {
        return $this->delete($this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));
    }

    /**
     * Retrieves all of the images for a particular gallery
     * @param $galleryId int GalleryId
     * @return array
     */
    public function getPicturesByGallery($galleryId)
    {
        return $this->fetchAll($this->select()->where("galleryId=?", $galleryId)->order($this->_primaryKey . " desc"))->toArray();
    }
}
