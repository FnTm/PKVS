<?php

class KomandaController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Turniru kopskatu darbība
     * @return void
     */
    public function indexAction()
    {
        $this->view->test = "Komandas";
    }

    public function pievienotAction()
    {
        $id = $this->_getParam('id', null);
        if ($id == NULL) {
            $this->log('Nav norādīts turnīra identifikators', 'error');
            $form = NULL;
        } else {
            $turniriModel = new Model_Turniri();
            $turnirs = $turniriModel->getTurnirs($id);
            if ($turnirs == NULL) {
                $this->log('Turnīrs neeksistē', 'error');
                $form = NULL;
            } else {
                $this->view->test = "Komanda";
                $form = new Form_Komanda_Pievienot();
                $this->view->form = $form;
                $form->setAction('/' . Zend_Registry::get('lang') . '/komanda/pievienot/id/' . $id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {
                        $KomandaModel = new Model_Komanda();
                        $data = $form->getValidValues($data);
                        $data['teamOwner'] = Zend_Auth::getInstance()->getIdentity()->userId;
                        $data['tournamentId'] = $id;
                        $KomandaModel->createKomanda($data);
                        $this->log('Komanda pievienota.', 'success');
                        $this->_redirect('/turniri/skatit/id/' . $id);
                    } else {
                        $form->populate($data);
                    }
                }
            }
        }
    }

    public function redigetAction()
    {

        $form = new Form_Komanda_Pievienot();
        $form->getElement('submit')->setLabel('Saglabāt');
        $komandaModel = new Model_Komanda();

        $id = $this->_getParam('kId', null);
        if ($id == NULL) {
            $this->log('Nav norādīts komandas identifikators', 'error');
            $form = NULL;

        }
        else {
            $komanda = $komandaModel->getKomanda($id);
            if ($komanda['teamOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                $data = $komandaModel->getKomanda($id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {
                        $komandaModel->updateKomanda($id, $form->getValidValues($data));
                        $this->log('Komanda rediģēta veiksmīgi', 'success');
                        $this->_redirect('/komanda/skatit/kId/' . $id);
                    }
                    else {
                        $form->populate($data);
                    }

                }
                else {
                    $form->populate($data);
                }


            }
            else {
                $this->log('Jums nav tiesību labot šo bildi', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }

    public function dzestAction()
    {
        $id = $this->_getParam('id', NULL);
        $komandaModel = new Model_Komanda();
        $komanda = $komandaModel->getKomanda($id);
        if ($komanda != NULL) {
            if ($komanda['teamOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $komandaModel->deleteKomanda($id);
                $this->log('Komanda ir dzēsta', 'success');

            } else {
                $this->log('Komanda, ko vēlējāties dzēst, nepieder Jums.', 'error');
            }
            $this->_redirect('/turniri/skatit/id/' . $komanda['tournamentId']);
        }
    }

}

?>
