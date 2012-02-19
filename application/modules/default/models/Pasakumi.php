<?php
/**
 * User: Janis
 * Date: 11.12.12
 * Time: 09:37
 */

class Model_Pasakumi extends Zend_Db_Table_Abstract
{
    public $_name = "pasakumi";
    public $_rowClass = "Model_Pasakumi_Row";

    public function createTurnirs($data)
    {
        $data['tournamentOwner'] = Zend_Auth::getInstance()->getIdentity()->userId;
        return $this->insert($data);

    }

    //TODO Sasaiste ar pasākuma izveidotāju, kategoriju
    public function getAllPasakumi()
    {
        return $this->fetchAll();
    }
//TODO Sasaiste ar pasākuma izveidotāju, kategoriju
    public function getPasakums($id)
    {
        return $this->fetchAll($this->select()->where("pasakumsId=?", $id));
    }



    public function updatePasakums($id, $data)
    {
        $where = $this->getAdapter()->quoteInto('pasakumsId = ?', $id);
        $this->update($data, $where);
    }


    /**
     * Atgriež tuvāko pasākumu
     * @return array
     */
    public function getClosestPasakums()
    {

        $adapter = $this->getAdapter();
        $sql = $adapter->select()->from($this->_name, new Zend_Db_Expr('* , ABS( UNIX_TIMESTAMP(  `time` ) - UNIX_TIMESTAMP( NOW( ) ) ) AS diff'))->join(array('u' => 'users'), $this->_name . '.tournamentOwner=u.userId')->order('diff asc');
        return $adapter->fetchAll($sql);

    }

    public function getAllTurniri($order)
    {
        $adapter = $this->getAdapter();
        $sql = $this->fullSql()->order('title ' . $order);
        return $adapter->fetchAll($sql);

    }

    protected function fullSql()
    {
        $adapter = $this->getAdapter();
        $sql = $adapter->select()->from($this->_name)->join(array('u' => 'users'), $this->_name . '.tournamentOwner=u.userId');
        return $sql;
    }

    public function getTurniriByUser($id)
    {
        $select = $this->select()->where('tournamentOwner=?', $id);
        $result = $this->fetchAll($select);
        if ($result === NULL) {
            return NULL;
        }
        else {
            return $result;
        }
    }

    /**
     * @param $userId UserId
     * @param $id Tournament ID
     * @return bool
     */
    public function isParticipating($userId, $id)
    {

        $adapter = $this->getAdapter();
        $sql = $this->fullSql()->join(array('t' => 'teams'), $this->_name . '.tournamentId=t.tournamentId')->join(array('tm' => 'teammembers'), 't.teamId=tm.teamId')->where($this->_name . '.tournamentId=?', $id)->where('memberId=?', $userId);
        $ret = $adapter->fetchRow($sql);
        if ($ret && $ret != NULL) {
            return true;
        }
        return false;
    }

    public function getFullUserListQuery()
    {

        $adapter = $this->getAdapter();
        $sql = $this->fullSql()->join(array('t' => 'teams'), $this->_name . '.tournamentId=t.tournamentId')->join(array('tm' => 'teammembers'), 't.teamId=tm.teamId');
        return $sql;
    }

    public function getTournamentsByUser($id)
    {
        $adapter = $this->getAdapter();
        $sql = $adapter->select()->from($this->_name)->join(array('t' => 'teams'), $this->_name . '.tournamentId=t.tournamentId')->join(array('tm' => 'teammembers'), 't.teamId=tm.teamId')->join(array('u' => 'users'), 'tm.memberId=u.userId')->where('u.userId=?', $id);
        // var_dump($sql->assemble());
        return $adapter->fetchAll($sql);
    }

    public function getByUserAndTournament($userId, $id)
    {
        $adapter = $this->getAdapter();
        $sql = $this->fullSql()->join(array('t' => 'teams'), $this->_name . '.tournamentId=t.tournamentId')->join(array('tm' => 'teammembers'), 't.teamId=tm.teamId')->where($this->_name . '.tournamentId=?', $id)->where('memberId=?', $userId);
        $ret = $adapter->fetchRow($sql);
        return $ret;

    }

}
