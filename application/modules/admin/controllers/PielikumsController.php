<?php

class PielikumsController extends JP_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->view->test = "Pielikumi";
    }
    public function skatitAction(){
        $id=$this->_getParam('id');
        $pielikumsModel=new Model_Pielikums();
        $this->view->data=$pielikums=$pielikumsModel->getPielikums($id);
    }
    public function pievienotAction() {
        $id = $this->_getParam('id', NULL);
        if ($id == NULL) {
            $this->log('Nav norādīts turnīra identifikators', 'error');
            $form = NULL;
        } else {
            $turniriModel = new Model_Turniri();
            $turnirs = $turniriModel->getTurnirs($id);
            if ($turnirs == NULL) {
                $this->log('Turnīrs neeksistē', 'error');
                $form = NULL;
            } else if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId) {
                $this->view->test = "Pielikums";
                $form = new Form_Pielikums_Pievienot();
                $this->view->form = $form;
                $form->setAction('/' . Zend_Registry::get('lang') . '/pielikums/pievienot/id/' . $id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {
                        $data = $this->_getAllParams();
                        if ($form->isValid($data)) {
                            $uploadField = 'file';
                            $pielikumsModel = new Model_Pielikums();

                            $upload = new Zend_File_Transfer_Adapter_Http ();
                            $upload->setDestination(ATTACHMENT_PATH . "/");
                            try {
                                // upload received file(s)
                                $upload->receive();
                            } catch (Zend_File_Transfer_Exception $e) {
                                $e->getMessage();
                            }
                            $name = $upload->getFileName($uploadField);
                            $ext = pathinfo($name, PATHINFO_EXTENSION);

                            $renameFile = $this->genRandomString() . "." . $ext;

                            /** @var $fullFilePath Links sistēmā, uz faila konkrēto atrašanās vietu */
                            $fullFilePath = ATTACHMENT_PATH . "/" . $renameFile;
                            /** @var $fullPropsPath Links izmantojot domēnu */
                            $fullPropsPath = ATTACHMENT_RESOURCE_PATH . "/uploaded/" . $renameFile;
                            //echo $fullPropsPath;
                            // Rename uploaded file using Zend Framework
                            $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));

                            $filterFileRename->filter($name);

                            $insertData = $form->getValidValues($data);
                            $insertData[$uploadField] = $fullPropsPath;
                            $insertData['tournamentId'] = $id;
                            if (false !==$pielikumsModel->createPielikums($insertData)) {
                                $this->log('Izdevās pievienot pielikumu', 'success');
                                $this->_redirect('/turnirs/skatit/id/' . $id);
                            }
                        } else {

                            $form->populate($data);
                        }
                    }
                }
            }
        }
    }

public function redigetAction()
    {

        $form = new Form_Pielikums_Pievienot();
        $form->removeElement('file');
        $form->getElement('submit')->setLabel('Saglabāt');
        $pielikumiModel = new Model_Pielikums();

        $id = $this->_getParam('pId', null);
        if ($id == NULL) {
            $this->log('Nav norādīts pielikuma identifikators', 'error');
            $form = NULL;

        }
        else {
            $data = $pielikumiModel->getPielikums($id);
            $turnirimodel = new Model_Turniri();
            $turnirs = $turnirimodel->getTurnirs($data['tournamentId']);
            if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {

                        $pielikumiModel->updatePielikums($id, $form->getValidValues($data));
                        $this->log('Pielikums rediģēts veiksmīgi', 'success');
                        $this->_redirect('/pielikums/skatit/pId/' . $id);
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
                $this->log('Jums nav tiesību labot šo pielikumu', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }
    public function dzestAction() {
        $id = $this->_getParam('id', NULL);
        if ($id == NULL) {
            $this->log('Nav norādīts pielikuma identifikators', 'error');
            $form = NULL;
        } else {
            $PielikumsModel = new Model_Pielikums();
            try {
                $Pielikums = $PielikumsModel->getPielikums($id);
            } catch (Exception $e) {
                $Pielikums = NULL;
                $this->log('Šāds pielikums neeksistē', 'error');
                $form = NULL;
            }
            $turnirimodel = new Model_Turniri();
            $turnirs = $turnirimodel->getTurnirs($Pielikums['tournamentId']);
            if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $PielikumsModel->deletePielikums($id);
                $this->log('Pielikums dzēsts', 'success');
                $this->_redirect('/turniri/skatit/id/'.$turnirs['tournamentId']);
            }
            else
                $this->log('Nav tiesību dzēst', 'error');
        }
    }

}