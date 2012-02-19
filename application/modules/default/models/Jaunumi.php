<?php

    class Model_Jaunumi extends Zend_Db_Table_Abstract

    {

    protected $_name = "news";

    public function createJaunumi($data){
       $data['ownerId']=Zend_Auth::getInstance()->getIdentity()->userId;
       return $this->insert($data);
    }
    public function getJaunums($id){
        $select=$this->select()->where('newsId=?',$id);
        $result=$this->fetchRow($select);
        if($result===NULL){
            /** @todo Add logging to email */
            throw new Exception('No row by this Id has been found');
        }
        else{
            return $result->toArray();
        }
    }
            public function updateJaunums($id,$data){
        $where = $this->getAdapter()->quoteInto('newsId = ?', $id);
        $this->update($data, $where);
    }
        public function deleteJaunumiByTournament($id){
        $select = $this->select()->where('tournamentId=?',$id);
        $jaunumi = $this->fetchAll($select);
        $jaunumsmodel = new Model_Jaunumi;
        foreach($jaunumi as $data){
            $jaunumsmodel->deleteJaunums($data['newsId']);
        }
    }
    public function deleteJaunums($id){
        $where = $this->getAdapter()->quoteInto("newsId = ?", $id);
        $this->delete($where);
    }
    public function getAllJaunumi(){

        $select =  $this->select()->order("time desc");
        return $this->fetchAll($select);
    }
    }
?>