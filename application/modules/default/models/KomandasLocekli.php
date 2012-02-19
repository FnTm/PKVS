<?php

class Model_KomandasLocekli extends Zend_Db_Table_Abstract {

    protected $_name = "teamMembers";

    public function createKomandasLoceklis($data) {
        return $this->insert($data);
    }

    public function deleteKomandasLoceklis($id, $id2) {
        $where1 = $this->getAdapter()->quoteInto("memberId = ?", $id);
        $where2 = $this->getAdapter()->quoteInto("teamId = ?", $id2);
        $this->delete($where1 . " AND " . $where2);
    }

    public function deleteKomandasLoceklisByTeam($id) {
        $select = $this->select()->where('teamId=?', $id);
        $komandasLoceklis = $this->fetchAll($select);
        $komandasLoceklismodel = new Model_KomandasLocekli();
        foreach ($komandasLoceklis as $data) {
            $komandasLoceklismodel->deleteKomandasLoceklis($data['memberId'], $id);
        }
        }

        public function getTurniriByUser($id){
        $query = sprintf('SELECT * FROM tournaments tourn JOIN teams tm ON tourn.tournamentId = tm.tournamentId JOIN teamMembers mb ON tm.teamId = mb.teamId WHERE mb.memberId =' . $id);
        $turniri = mysql_query($query);
        return $turniri;
    }

}

?>