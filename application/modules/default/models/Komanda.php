<?php

class Model_Komanda extends Zend_Db_Table_Abstract

{

    protected $_name = "teams";

    public function createKomanda($data)
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        $komanduModel = new Model_KomandasLocekli();
        if ($ins = $this->insert($data)) {
            $html = new Zend_View();


            $mail = new Zend_Mail('UTF-8');
            $transport = new Zend_Mail_Transport_Smtp();
            $mail->setSubject('Turnīrs izveidots!');
            $mail->addTo($user['email'], $user['name']);
            $mail->setFrom('support@turniri.lv','Turniri');
            $mail->setBodyHtml("Paldies, ka pievienojāt Turnīru!\n\r<br/> Lai citi varētu pieteikties jūsu komandā, izmantojiet šo saiti: ".APP_DOMAIN."/".Zend_Registry::get('lang')."/komandas-locekli/pievienot/id/".$data['teamId'],'UTF-8','UTF-8');
            $mail->send($transport);

        }
        unset($data);
        $data['teamId'] = $ins;
        $data['memberId'] = Zend_Auth::getInstance()->getIdentity()->userId;
        $komanduModel->createKomandasLoceklis($data);
        return $ins;
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