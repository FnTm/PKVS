<?php

class IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $jaunumiModel=new Model_Jaunumi();
        $this->view->jaunumi=$jaunumiModel->getAllJaunumi();
        
    }
    public function changeAction(){
        $this->getHelper('viewRenderer')->setNoRender();
    }
    public function clearLanguageAction(){

        setcookie('lang', true, 1, '/');
        $this->_redirect('/');
    }


}

