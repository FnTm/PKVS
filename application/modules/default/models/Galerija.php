<?php

class Model_Galerija extends Zend_Db_Table_Abstract

{

    protected $_name = "galleries";

    public function createGalerija($data)
    {
        return $this->insert($data);
    }

    public function getGalerija($id)
    {
        $select = $this->select()->where('galleryId=?', $id);
        $result = $this->fetchRow($select);
        if ($result === NULL) {
            /** @todo Add logging to email */
            throw new Exception('No row by this Id has been found');
        }
        else {
            return $result->toArray();
        }
    }

    public function deleteGalerija($id)
    {
        /** @todo delete bildes **/
        $picturemodel = new Model_Bildes();
        $picturemodel->deleteBildeByGalerija($id);
        $where = $this->getAdapter()->quoteInto("galleryId = ?", $id);
        $this->delete($where);
        return true;
    }

    public function updateGalerija($id, $data)
    {
        $where = $this->getAdapter()->quoteInto('galleryId = ?', $id);
        $this->update($data, $where);
    }

    public function deleteGalerijaByTournament($id)
    {
        $select = $this->select()->where('tournamentId=?', $id);
        $galleries = $this->fetchAll($select);
        $gallerymodel = new Model_Galerija;
        foreach ($galleries as $data) {
            $gallerymodel->deleteGalerija($data['galleryId']);
        }
    }

    public function getGalleryByTournament($id)
    {
        return $this->fetchAll($this->select()->where('tournamentId=?', $id))->toArray();
    }
    public function getPictureForGallery($id){
        $adapter=$this->getAdapter();
        $sql=$adapter->select()->from($this->_name)->join(array('p'=>'pictures'),$this->_name.'.galleryId=p.galleryId')->where('p.galleryId=?',$id);
        return $adapter->fetchAll($sql);
    }
}

?>