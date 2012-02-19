<?php

class Admin_JaunumiController extends JP_Controller_Action {

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
            $this->log('Nav norādīts turnīra identifikators', 'error');
            $form = NULL;
        } else {
            $turniriModel = new Model_Pasakumi();
            $turnirs = $turniriModel->getTurnirs($id);
            if ($turnirs == NULL) {
                $this->log('Turnīrs neeksistē', 'error');
                $form = NULL;
            } else if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId) {


                $this->view->test = "Jaunumi";
                $form = new Form_Jaunumi_Pievienot();
                $this->view->form = $form;
                $form->setAction('/' . Zend_Registry::get('lang') . '/jaunumi/pievienot/id/' . $id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                     $data['tournamentId'] = $id;
                    if ($form->isValid($data)) {
                        $JaunumiModel = new Model_Jaunumi();
                        $JaunumiModel->createJaunumi($form->getValidValues($data));
                        $this->log('Jaunums pievienots.', 'success');
                    } else {
                        $form->populate($data);
                    }
                }
            }
        }
    }

public function redigetAction()
    {

        $form = new Form_Jaunumi_Pievienot();
        $form->getElement('submit')->setLabel('Saglabāt');
        $jaunumiModel = new Model_Jaunumi();

        $id = $this->_getParam('jId', null);
        if ($id == NULL) {
            $this->log('Nav norādīts jaunuma identifikators', 'error');
            $form = NULL;

        }
        else {
            $jaunums = $jaunumiModel->getJaunums($id);
            if ($jaunums['ownerId'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {

                        $jaunumiModel->updateJaunums($id, $form->getValidValues($data));
                        $this->log('Turnīrs rediģēts veiksmīgi', 'success');
                        $this->_redirect('/jaunumi/skatit/jId/' . $id);
                    }
                    else {
                        $form->populate($jaunums);
                    }

                }
                else {
                    $form->populate($jaunums);
                }


            }
            else {
                $this->log('Jums nav tiesību labot šo bildi', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }
    public function dzestAction() {
        $id = $this->_getParam('id', NULL);
        $jaunumiModel = new Model_Jaunumi();
        $jaunums = $jaunumiModel->getJaunums($id);
        if ($jaunums != NULL) {
            if ($jaunums['ownerId'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $jaunumiModel->deleteJaunums($id);
                $this->log('Jaunums ir dzēsts', 'success');
            } else {
                $this->log('Jaunums, ko vēlējāties dzēst, nepieder Jums.', 'error');
            }
        }
    }

}

?>
