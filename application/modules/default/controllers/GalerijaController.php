<?php

class GalerijaController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->test = "Galerija";
    }

    public function pievienotAction()
    {
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
                $this->view->test = "Galerija";
                $form = new Form_Galerija_Pievienot();
                $this->view->form = $form;
                $form->setAction('/' . Zend_Registry::get('lang') . '/galerija/pievienot/id/' . $id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {
                        $GalerijaModel = new Model_Galerija();
                        $data = $form->getValidValues($data);
                        $data['tournamentId'] = $id;
                        $row = $GalerijaModel->createGalerija($data);
                        $this->_redirect('/bildes/pievienot/id/' . $row);
                    } else {
                        $form->populate($data);
                    }
                }
            }
        }
    }

    public function redigetAction()
    {

        $form = new Form_Galerija_Pievienot();
        $form->getElement('submit')->setLabel('Saglabāt');
        $galerijaModel = new Model_Galerija();

        $id = $this->_getParam('gId', null);
        if ($id == NULL) {
            $this->log('Nav norādīts galerijas identifikators', 'error');
            $form = NULL;

        }
        else {
            $galerija = $galerijaModel->getGalerija($id);
            $turniriModel = new Model_Turniri();
            $turnirs = $turniriModel->getTurnirs($galerija['tournamentId']);
            if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                if ($this->getRequest()->isPost()) {
                    $galerija = $this->_getAllParams();
                    if ($form->isValid($galerija)) {

                        $galerijaModel->updategalerija($id, $form->getValidValues($galerija));
                        $this->log('Galerija rediģēta veiksmīgi', 'success');
                        $this->_redirect('/galerija/skatit/gId/' . $id);
                    }
                    else {
                        $form->populate($galerija);
                    }

                }
                else {
                    $form->populate($galerija);
                }


            }
            else {
                $this->log('Jums nav tiesību labot šo galeriju', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }

    public function dzestAction()
    {
        $id = $this->_getParam('gId', NULL);
        if ($id == NULL) {
            $this->log('Nav norādīts galerijas identifikators', 'error');
            $form = NULL;
        } else {
            $galerijaModel = new Model_Galerija();
            try {
                $galerija = $galerijaModel->getGalerija($id);
            } catch (Exception $e) {
                $galerija = NULL;
                $this->log('Šāda galerija neeksistē', 'error');
                $form = NULL;
            }
            $turnirimodel = new Model_Turniri();
            $turnirs = $turnirimodel->getTurnirs($galerija['tournamentId']);
            if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $galerijaModel->deleteGalerija($id);
            }

            else {
                $this->log('Nav tiesību dzēst', 'error');
            }
        }
    }

    public function skatitTurniramAction()
    {

        $id = $this->_getParam('id', NULL);
        if ($id == NULL) {
            $this->log('Nav norādīts turnira identifikators', 'error');
            $this->view->galleries = NULL;
        }
        else {
            $gModel = new Model_Galerija();
            $this->view->galleries = $gModel->getGalleryByTournament($id);
        }


        $this->addLink('/turniri/skatit/id/' . $id, 'Atpakaļ uz turnīru', 'u');

        $this->addLink('/turniri/pievienot/', 'Pievienot turnīru', 'u');
        $this->addLink('/turniri/mani/', 'Skatīt manus turnīrus', 'u');
        $this->addLink('/turniri/piedalos/', 'Skatīt manu dalību turnīriem', 'u');

    }

    public function skatitAction()
    {

        $id = $this->_getParam('id', NULL);
        if ($id == NULL) {
            $this->log('Nav norādīts galerijas identifikators', 'error');
            $this->view->pictures = NULL;
        }
        else {
            $gModel = new Model_Galerija();
            $this->view->pictures = $gModel->getPictureForGallery($id);
        }

        if ($this->view->pictures != NULL && !empty($this->view->pictures)) {

            $this->addLink('/galerija/skatit-turniram/id/' . $this->view->pictures[0]['tournamentId'], 'Atpakaļ uz turnīra galerijām', 'u');
            $this->addLink('/turniri/skatit/id/' . $this->view->pictures[0]['tournamentId'], 'Atpakaļ uz turnīru', 'u');
        }
        $this->addLink('/turniri/pievienot/', 'Pievienot turnīru', 'u');
        $this->addLink('/bildes/pievienot/id/'.$id, 'Pievienot bildi', 'u');



    }

}