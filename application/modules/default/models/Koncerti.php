<?php

class Model_Koncerti extends Zend_Db_Table_Abstract

{

    protected $_name = "koncerti";

    public function createKoncerts($data)
    {
        return $this->insert($data);
    }

    public function getKomanda($id)
    {
        $select = $this->select()->where('teamId=?', $id);
        $result = $this->fetchRow($select);

            return $result;

    }
    

    public function deleteKomandaByTournament($id)
    {
        $select = $this->select()->where('tournamentId=?', $id);
        $komanda = $this->fetchAll($select);
        $komandamodel = new Model_Komanda;
        foreach ($komanda as $data) {
            $komandamodel->deleteKomanda($data['teamId']);
        }
    }

    public function updateKomanda($id, $data)
    {
        $where = $this->getAdapter()->quoteInto("teamId = ?", $id);
        $this->update($data, $where);
    }

    public function deleteKomanda($id)
    {
        ///@todo delete teamplayers
        $teamMembersModel = new Model_KomandasLocekli();
        $teamMembersModel->deleteKomandasLoceklisByTeam($id);
        $where = $this->getAdapter()->quoteInto("teamId = ?", $id);
        $this->delete($where);
    }

}

?>