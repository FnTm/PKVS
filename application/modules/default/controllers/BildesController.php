<?php

class BildesController extends JP_Controller_Action
{


    /**
     * @return void
     */
    public function indexAction()
    {
        $this->_redirect('/');
    }

    /* Jaunas bildes pievienošanas darbība
     * @return void
     */

    public function pievienotAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai pievienotu bildi, lūdzu ienāciet sistēmā!', 'error');
            $this->_redirect('/');

        }
        $gallery = $this->_getParam('id', NULL);
        if ($gallery !== NULL) {
            $this->view->test = "Turniri";
            $form = new Form_Bildes_Pievienot();
            $this->view->form = $form;
            $form->setAction('/' . Zend_Registry::get('lang') . '/bildes/pievienot/id/' . $gallery);
            $uploadField = 'picture';
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $bildeModel = new Model_Bildes();

                    $upload = new Zend_File_Transfer_Adapter_Http ();
                    $upload->setDestination(UPLOAD_PATH . "/");
                    try {
                        // upload received file(s)
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }


                    $insertData = $form->getValidValues($data);
                    $insertData['galleryId'] = $gallery;
                    if (false !== $id = $bildeModel->createBilde($insertData)) {
                        $this->log('Izdevās pievienot bildi', 'success');
                        $name = $upload->getFileName($uploadField);
                        $ext = pathinfo($name, PATHINFO_EXTENSION);

                        $renameFile = $id . "." . $ext;

                        /** @var $fullFilePath Links sistēmā, uz faila konkrēto atrašanās vietu */
                        $fullFilePath = UPLOAD_PATH . "/" . $renameFile;
                        /** @var $fullPropsPath Links izmantojot domēnu */
                        $fullPropsPath = RESOURCE_PATH . "/uploaded/" . $renameFile;
                        //echo $fullPropsPath;
                        // Rename uploaded file using Zend Framework
                        $filterFileRename = new Zend_Filter_File_Rename (array('target' => $fullFilePath, 'overwrite' => true));

                        $filterFileRename->filter($name);
                        $this->_redirect('/galerija/skatit/id/' . $gallery);
                    }


                }
                else {

                    $form->populate($data);
                }
            }
        }
        else {

            $this->log('Nav norādīts galerijas identifikators', 'error');
        }
    }

    /*     * Esoša turnīra rediģēšanas darbība
     * @return void
     */

    public function redigetAction()
    {

        $form = new Form_Bildes_Pievienot();
        $form->removeElement('picture');
        $form->getElement('submit')->setLabel('Saglabāt');
        $bildesModel = new Model_Bildes();

        $id = $this->_getParam('id', null);
        if ($id == NULL) {
            $this->log('Nav norādīts bildes identifikators', 'error');
            $form = NULL;

        }
        else {
            $bilde = $bildesModel->getBildeOwner($id);
            if ($bilde[0]['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {

                        $bildesModel->editBilde($id, $form->getValidValues($data));
                        $this->log('Bilde rediģēta veiksmīgi', 'success');
                        $this->_redirect('/bildes/skatit/id/' . $id);
                    }
                    else {
                        $form->populate($data);
                    }

                }
                else {
                    $form->populate($bilde[0]);
                }


            }
            else {
                $this->log('Jums nav tiesību labot šo bildi', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }

    public function skatitAction()
    {
        $id = $this->_getParam('id', null);
        if ($id == NULL) {
            $this->log('Nav norādīts bildes identifikators', 'error');
        }
        else {
            $bildeModel = new Model_Bildes();
            $this->view->picture = $turnirs = $bildeModel->getBilde($id);
            if (Zend_Auth::getInstance()->hasIdentity() && ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a')) {
                $this->addLink('/bildes/dzest/id/' . $turnirs['bildeId'], 'Dzēst bildi', 'u');
            }
        }

    }

    public function dzestAction()
    {
        $id = $this->_getParam('id', NULL);
        $turniriModel = new Model_Bildes();
        $bilde = $turniriModel->getBilde($id);
        if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
            $turniriModel->deleteBilde($id);
            $this->_redirect('/turniri/');
        }
        else
        {
            $this->view->test = "Turnīri";
        }
        $this->_redirect('/turniri/');
    }

}

