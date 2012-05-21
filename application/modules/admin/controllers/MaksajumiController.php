<?php

class Admin_MaksajumiController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    //TODO Parādīt katra kopējo bilanci
    public function indexAction()
    {
        $maksajumsModel = new Model_Maksajumi();
        $this->view->bilance = $maksajumsModel->getBilanceForAll();
        $this->view->maksajumi = $maksajumsModel->getMaksajumi();

    }

    public function pievienotMaksajumuAction()
    {
        $userModel = new Model_Users();
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot($userModel->getUsers(true, array('name asc')));
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            // var_dump($data);
            if ($form->isValid($data)) {
                $maksajumsModel = new Model_Maksajumi();
                $maksajumsModel->createMaksajums($form->getValidValues($data));
                $this->log("Maksājums veiksmīgi pievienots", self::SUCCESS);
                $this->_redirect("/admin/maksajumi");

            }
            else {
                $form->populate($data);
            }
        }
    }

    public function redigetMaksajumuAction()
    {
        $userModel = new Model_Users();
        $maksajumsModel = new Model_Maksajumi();
        $id = $this->_getParam("id", null);
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot($userModel->getUsers(true, array('name asc')));
        $form->removeElement("maksajumsUserId");
        $form->removeElement("maksajumsValue");
        if (!is_null($id)) {
            $form->setAction("/admin/maksajumi/rediget-maksajumu/id/" . $id);
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $maksajumsModel->editMaksajums($form->getValidValues($data), $id);
                    $this->log("Maksājums veiksmīgi rediģēts", self::SUCCESS);
                    $this->_redirect("/admin/maksajumi");
                }
                else {
                    $form->populate($data);
                }
            }
            else {
                $form->populate($maksajumsModel->getMaksajums($id));
            }
        }
    }

    public function pievienotMaksajumuVairakiemAction()
    {
        $userModel = new Model_Users();
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot_Vairakiem($userModel->getUsers(true, array('name asc')));
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            // var_dump($data);
            if ($form->isValid($data)) {
                $maksajumsModel = new Model_Maksajumi();
                $userArray = array();
                $data = $form->getValidValues($data);
                /*var_dump($data);
                exit;
                */
                $maksajumsModel->createMultiMaksajums($data);
                //$maksajumsModel->createMaksajums($form->getValidValues($data));
                $this->log("Maksājums veiksmīgi pievienots", self::SUCCESS);
                $this->_redirect("/admin/maksajumi");

            }
            else {
                $form->populate($data);
            }
        }
    }

    public function pievienotIemaksuAction()
    {

    }

    public function apstiprinatAction()
    {
        $id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $maksajumsModel = new Model_Maksajumi();
            $maksajumsModel->changeMaksajums($id, 1);
            $this->log("Maksājums apstiprināts", self::SUCCESS);
            $this->_redirect("/admin/maksajumi");
        }
    }

    public function noraiditAction()
    {
        $id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $maksajumsModel = new Model_Maksajumi();
            $maksajumsModel->changeMaksajums($id, 0);
            $this->log("Maksājums noraidīts", self::SUCCESS);
            $this->_redirect("/admin/maksajumi");
        }
    }


}

