<?php

    class Model_Pielikums extends Zend_Db_Table_Abstract

    {

    protected $_name = "attachments";

    public function createPielikums($data){
       return $this->insert($data);
    }
    public function getPielikums($id){
        $select=$this->select()->where('attachmentId=?',$id);
        $result=$this->fetchRow($select);
        if($result===NULL){
            /** @todo Add logging to email */
            throw new Exception('No row by this Id has been found');
        }
        else{
            return $result->toArray();
        }
    }
        public function updatePielikums($id, $data) {
        $where = $this->getAdapter()->quoteInto("attachmentId = ?", $id);
        $this->update($data, $where);
    }
     public function getPielikumiByTournament($id){
         return $this->fetchAll($this->select()->where('tournamentId',$id))->toArray();
     }
    public function deletePielikumiByTournament($id){
        $select = $this->select()->where('tournamentId=?',$id);
        $pielikumi = $this->fetchAll($select);
        $pielikumimodel = new Model_Pielikums;
        foreach($pielikumi as $data){
            $pielikumimodel->deletePielikums($data['attachmentId']);
        }
    }
    public function deletePielikums($id){
        $where = $this->getAdapter()->quoteInto("attachmentId = ?", $id);
        $this->delete($where);
    }
    }
?>