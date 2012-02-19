<?php

class NomaController extends JP_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    /**
     * Turniru kopskatu darbība
     * @return void
     */
    public function indexAction() {
        $nomaModel = new Model_Noma();
        $this->view->noma = $nomaModel->get_all_Noma();
        $this->view->test = "Noma";
    } 

   public function pievienotAction() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai pievienotu nomas piedāvājumu, lūdzu, ienāciet sistēmā', 'error');
           $this->_redirect('/');
        }
        else if(Zend_Auth::getInstance()->getIdentity()->role==='a'){
            $this->view->test = "Noma";
            $form = new Form_Noma_Pievienot();
            $this->view->form = $form;
            $form->setAction('/' . Zend_Registry::get('lang') . '/noma/pievienot');
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $nomaModel = new Model_Noma();
                    $nomaModel->createNoma($form->getValidValues($data));
                    $this->log('Nomas piedāvājums pievienots.', 'success');
                } else {
                    $form->populate($data);

                }
            }
        } else {
            $this->log('Jums nav tiesību pievienot nomas piedāvājumu', 'error');
        }
    }

public function redigetAction()
    {

        $form = new Form_Noma_Pievienot();
        $form->getElement('submit')->setLabel('Saglabāt');
        $nomaModel = new Admin_Model_Noma();

        $id = $this->_getParam('nId', null);
        if ($id == NULL) {
            $this->log('Nav norādīts nomas identifikators', 'error');
            $form = NULL;


        }
        else {
            if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                $data = $nomaModel->getNoma($id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {
                        $nomaModel->updateNoma($form->getValidValues($data),$id);
                        $this->log('Noma rediģēta veiksmīgi', 'success');
                        $this->_redirect('/noma/skatit/id/' . $id);
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
                $this->log('Jums nav tiesību labot nomas piedāvājumu', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }
    public function dzestAction() {
        $id = $this->_getParam('nId', NULL);

        if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
            $nomaModel = new Model_Noma();
            $nomaModel->deleteNoma($id);
            $this->log('Nomas piedāvājums ir dzēsts', 'success');
        } else {
            $this->log('Jums nav tiesību dzēst nomas piedāvājumu.', 'error');
        }
    }

}

?>
