<?php

class Admin_IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $turniriModel=new Model_Pasakumi();
        $this->view->tuvakie=$turniriModel->getAllPasakumi();


    }
    public function changeAction(){
        $this->getHelper('viewRenderer')->setNoRender();
    }


}

