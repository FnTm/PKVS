<?php

class KomandasLocekliController extends JP_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    /**
     * Turniru kopskatu darbība
     * @return void
     */
    public function indexAction() {
        $this->view->test = "Jaunumi";
    }

    public function pievienotAction() {
        $id = $this->_getParam('id', null);
        if ($id == NULL) {
            $this->log('Nav norādīts komandas identifikators', 'error');
            $form = NULL;
        } else {
            $komandaModel = new Model_Komanda();
            $komanda = $komandaModel->getKomanda($id);
            if ($komanda == NULL) {
                $this->log('Komanda neeksistē', 'error');
                $form = NULL;
            } else{
                $data['teamId'] = $id;
                $data['memberId'] = Zend_Auth::getInstance()->getIdentity()->userId;
                if(Zend_Auth::getInstance()->getIdentity()->role == 'b'){
                    $this->log('Jūs nevarat pievienoties komandai', 'error');
                }
                else{
                    $komandasLocekliModel = new Model_KomandasLocekli();
                    $komandasLocekliModel->createKomandasLoceklis($data);
                    $this->log('Jūs esat veiksmīgi pievienojies komandai', 'success');
                    $this->_redirect('/turniri/skatit/id/.'.$komanda->tournamentId);
                }
            }
        }
    }
    public function dzestAction() {
        $loceklisId = $this->_getParam('lId', NULL);
        $komandasId = $this->_getParam('kId', NULL);
        $komandasLocekliModel = new Model_KomandasLocekli();
        /*$komandasLoceklis = $komandasLocekliModel->getKomandasLoceklis($loceklisId,$komandasId);
        if ($jkomandasLoceklis != NULL) {*/
            if ($loceklisId === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $komandasLocekliModel->deleteKomandasLoceklis($loceklisId,$komandasId);
                $this->log('Jūs esat dzēsts no komandas', 'success');
            } else {
                $this->log('Jūs nevarat izdzēst citu komandas locekli.', 'error');
            ///}
        }
    }


}

?>
