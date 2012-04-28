<?php

class Admin_MaksajumiController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $pasakumiModel=new Model_Pasakumi();
        $this->view->pasakumi=$pasakumiModel->getAllPasakumi();


    }
    public function changeAction(){
        $this->getHelper('viewRenderer')->setNoRender();
    }


}

