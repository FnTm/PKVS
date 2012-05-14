<?php

class IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
            	    	//$this->_helper->layout->disableLayout();
        /** TODO Jāpievieno agregators */
        if(Zend_Auth::getInstance()->hasIdentity()){
       /* $jaunumiModel=new Model_Jaunumi();
        $this->view->jaunumi=$jaunumiModel->getAllJaunumi();
       */
            $pasakumiModel=new Model_Pasakumi();
            $this->view->pasakumi=$pasakumiModel->getClosestPasakumi();
        }
        else{
            $this->_helper->layout->disableLayout();
            $this->renderScript("index/index_guest.phtml");
        }
        
    }

}

