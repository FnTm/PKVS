<?php

class IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $turniriModel=new Model_Turniri();
        $this->view->tuvakie=$turniriModel->getClosestTurniri();
        $this->addLink('/turniri/pievienot/','add_tournament','u');

    }
    public function changeAction(){
        $this->getHelper('viewRenderer')->setNoRender();
    }
    public function clearLanguageAction(){

        setcookie('lang', true, 1, '/');
        $this->_redirect('/');
    }


}

