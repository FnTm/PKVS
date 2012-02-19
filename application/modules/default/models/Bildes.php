<?php

/**
 * User: Janis
 * Date: 11.12.12
 * Time: 09:37
 */
class Model_Bildes extends Zend_Db_Table_Abstract {

    public $_name = "pictures";

    public function createBilde($data) {
        return $this->insert($data);
    }

    public function getBilde($id) {
        $adapter = $this->getAdapter();
        $select = $this->getFullSelect()->where('pictureId=?', $id);
        return $adapter->fetchRow($select);
    }

    public function deleteBilde($id) {
        $where = $this->getAdapter()->quoteInto("pictureId = ?", $id);
        $this->delete($where);
    }

    public function getFullSelect() {
        $adapter = $this->getAdapter();
        $sql = $adapter->select()->from($this->_name)->join(array('g' => 'galleries'), $this->_name . '.galleryId=g.galleryId')->join(array('t' => 'tournaments'), 'g.tournamentId=t.tournamentId');
        return $sql;
    }

    public function editBilde($id, $data) {
        $where = $this->getAdapter()->quoteInto("pictureId = ?", $id);
        $this->update($data, $where);
    }

    public function getBildeOwner($id) {
        /** @var $adapter Zend_Db_Adapter_Abstract */
        $adapter = $this->getAdapter();
        $select = $this->getFullSelect()->where('pictureId=?', $id);
        return $adapter->fetchAll($select);
    }

    public function deleteBildeByGalerija($id) {
        $select = $this->select()->where('galleryId=?', $id);
        $pictures = $this->fetchAll($select);
        $bildemodel= new Model_Bildes;
        foreach ($pictures as $data) {
            $bildemodel->deleteBilde($data['pictureId']);
        }
    }

}
