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
        $this->view->maksajumi = $maksajumsModel->getBilanceForAll();

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


}

